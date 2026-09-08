<?php

namespace Tests\Feature;

use App\Jobs\ScrapeNewsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\TestCase;

class ConsoleScheduleTest extends TestCase
{
    public function test_news_scraping_is_not_scheduled_and_inspire_remains_available(): void
    {
        $descriptions = collect($this->app->make(Schedule::class)->events())
            ->pluck('description')->all();

        self::assertNotContains('scrape-saintek-news', $descriptions);
        self::assertNotContains(ScrapeNewsJob::class, $descriptions);

        $this->artisan('inspire')->assertSuccessful();
    }
}
