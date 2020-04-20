<?php

namespace App\Console\Commands;

use App\Services\BambamCrawler;
use App\Services\CrawlerService;
use Illuminate\Console\Command;

class StartCrawlerCommand extends Command
{
    protected $signature = 'crawler:start {name}';
    protected $description = 'Executa o Crawler';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if (app(CrawlerService::class)->run($this->argument('name'))) {
            $this->info('BIRRLLLLLLLL!');
            return true;
        }

        $this->error('Ajuda o Maluco Tá Doente!');
        return false;
    }
}