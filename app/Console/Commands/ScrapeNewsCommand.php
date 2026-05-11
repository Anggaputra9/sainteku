<?php

namespace App\Console\Commands;

use App\Jobs\ScrapeNewsJob;
use Illuminate\Console\Command;

class ScrapeNewsCommand extends Command
{
    protected $signature   = 'news:scrape {--pages=1 : Jumlah halaman yang di-scrape}';
    protected $description = 'Scrape berita terbaru dari saintek.uinsaizu.ac.id';

    public function handle(): int
    {
        $pages = (int) $this->option('pages');

        $this->info("Dispatching ScrapeNewsJob untuk {$pages} halaman...");

        ScrapeNewsJob::dispatch($pages);

        $this->info('Job berhasil di-dispatch ke queue. Jalankan: php artisan queue:work');

        return self::SUCCESS;
    }
}