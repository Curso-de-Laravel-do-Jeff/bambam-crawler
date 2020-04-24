<?php

namespace App\Http;

use App\Core\Support\Controller;
use App\Services\CrawlerService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class CrawlerController extends Controller
{
    private $crawlerService;

    public function __construct(CrawlerService $crawlerService)
    {
        $this->crawlerService = $crawlerService;
    }

    public function exec(string $name)
    {
        Artisan::call('crawler:start', ['name' => $name]);

        return response()->json(['success' => 1, 'message' => 'Crawler ' . $name . ' Executado!'], Response::HTTP_OK);
    }
}
