<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class CrawlerService
{
    private $crawlers = [
        'bambam' => BambamCrawler::class
    ];

    public function run(string $name)
    {
        try {
            return app($this->crawlers[$name])->exec();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            dump("ERRO: " . $e->getMessage());
            return false;
        }
    }
}
