<?php

namespace App\Jobs;

use App\Models\ScrapedNews;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapeNewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Berapa halaman yang di-scrape (default 1, bisa ditambah)
     */
    public function __construct(private int $pages = 1)
    {
    }

    public function handle(): void
    {
        for ($page = 1; $page <= $this->pages; $page++) {
            $url = $page === 1
                ? 'https://saintek.uinsaizu.ac.id/category/berita/'
                : "https://saintek.uinsaizu.ac.id/category/berita/page/{$page}/";

            $this->scrapePage($url);

            // Jeda antar halaman biar ga spam server orang
            if ($page < $this->pages) {
                sleep(2);
            }
        }

        Log::info('[ScrapeNewsJob] Selesai scraping ' . $this->pages . ' halaman.');
    }

    private function scrapePage(string $url): void
    {
        try {
            $response = Http::timeout(15)
                ->withoutVerifying()
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (compatible; SaintekuBot/1.0)',
                    'Accept' => 'text/html,application/xhtml+xml',
                ])
                ->get($url);

            if (!$response->ok()) {
                Log::warning("[ScrapeNewsJob] Gagal fetch {$url}, status: " . $response->status());
                return;
            }

            $html = $response->body();
            $this->parseArticles($html);

        } catch (\Throwable $e) {
            Log::error("[ScrapeNewsJob] Error scraping {$url}: " . $e->getMessage());
        }
    }

    private function parseArticles(string $html): void
    {
        // Suppress XML warning, load HTML
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();

        $xpath = new \DOMXPath($doc);

        /**
         * Struktur WordPress saintek.uinsaizu.ac.id:
         * Setiap artikel ada di dalam <article> atau wrapper dengan <h2> + link
         * Kita target: gambar (img), tanggal, judul (h2 > a), excerpt (p)
         *
         * Selector XPath berurutan dari yang paling spesifik
         */

        // Coba selector artikel WordPress standar
        $articles = $xpath->query('//article') ?: $xpath->query('//*[contains(@class,"post")]');

        if ($articles && $articles->length > 0) {
            foreach ($articles as $article) {
                $this->extractFromArticleNode($xpath, $article);
            }
            return;
        }

        // Fallback: parse dari heading + gambar yang berdekatan
        $this->extractFallback($xpath);
    }

    private function extractFromArticleNode(\DOMXPath $xpath, \DOMNode $article): void
    {
        // Judul & URL
        $titleNode = $xpath->query('.//h2//a | .//h1//a | .//h3//a', $article)->item(0);
        if (!$titleNode)
            return;

        $title = trim($titleNode->textContent);
        $url = $titleNode->getAttribute('href');

        if (empty($title) || empty($url))
            return;

        // Gambar
        $imgNode = $xpath->query('.//img', $article)->item(0);
        $imageUrl = $imgNode ? ($imgNode->getAttribute('src') ?: $imgNode->getAttribute('data-src')) : null;

        // Tanggal
        $dateNode = $xpath->query('.//*[contains(@class,"date") or contains(@class,"time") or self::time]', $article)->item(0);
        $publishedAt = $dateNode ? $this->parseDate($dateNode->textContent) : null;

        // Excerpt
        $excerptNode = $xpath->query('.//p', $article)->item(0);
        $excerpt = $excerptNode ? trim($excerptNode->textContent) : null;

        $this->upsertNews($title, $url, $imageUrl, $excerpt, $publishedAt);
    }

    private function extractFallback(\DOMXPath $xpath): void
    {
        // Ambil semua h2 yang punya link (pola umum listing WordPress)
        $headings = $xpath->query('//h2[a] | //h2/a/..');

        foreach ($headings as $heading) {
            $link = $xpath->query('.//a', $heading)->item(0);
            if (!$link)
                continue;

            $title = trim($link->textContent);
            $url = $link->getAttribute('href');

            if (empty($title) || empty($url) || !str_contains($url, 'uinsaizu.ac.id'))
                continue;

            // Gambar: cari img sebelum heading ini (sibling atau parent)
            $parent = $heading->parentNode;
            $imageUrl = null;
            if ($parent) {
                $img = $xpath->query('.//img', $parent)->item(0)
                    ?: $xpath->query('preceding-sibling::*//img', $heading)->item(0);
                if ($img) {
                    $imageUrl = $img->getAttribute('src') ?: $img->getAttribute('data-src');
                }
            }

            // Tanggal: cari teks yang mirip tanggal di sekitar heading
            $publishedAt = null;
            $siblings = $xpath->query('preceding-sibling::* | following-sibling::*', $heading);
            foreach ($siblings as $sib) {
                $text = trim($sib->textContent);
                if (preg_match('/\d{1,2}\s+\w+\s+\d{4}/', $text)) {
                    $publishedAt = $this->parseDate($text);
                    break;
                }
            }

            // Excerpt
            $nextP = $xpath->query('following-sibling::p', $heading)->item(0);
            $excerpt = $nextP ? trim($nextP->textContent) : null;

            $this->upsertNews($title, $url, $imageUrl, $excerpt, $publishedAt);
        }
    }

    private function upsertNews(
        string $title,
        string $url,
        ?string $imageUrl,
        ?string $excerpt,
        ?Carbon $publishedAt
    ): void {
        ScrapedNews::updateOrCreate(
            ['url' => $url],
            [
                'title' => $title,
                'image_url' => $imageUrl,
                'excerpt' => $excerpt ? mb_substr($excerpt, 0, 500) : null,
                'published_at' => $publishedAt,
                'source' => 'saintek.uinsaizu.ac.id',
            ]
        );
    }

    private function parseDate(string $raw): ?Carbon
    {
        $raw = trim($raw);

        // Format: "22 April 2026"
        $months = [
            'januari' => 'January',
            'februari' => 'February',
            'maret' => 'March',
            'april' => 'April',
            'mei' => 'May',
            'juni' => 'June',
            'juli' => 'July',
            'agustus' => 'August',
            'september' => 'September',
            'oktober' => 'October',
            'november' => 'November',
            'desember' => 'December',
        ];

        $normalized = strtolower($raw);
        foreach ($months as $id => $en) {
            $normalized = str_replace($id, $en, $normalized);
        }

        try {
            return Carbon::parse($normalized);
        } catch (\Throwable) {
            return null;
        }
    }
}