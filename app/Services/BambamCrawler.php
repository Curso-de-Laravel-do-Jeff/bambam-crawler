<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Interfaces\CrawlerInterface;
use App\Jobs\AthleteJob;

class BambamCrawler implements CrawlerInterface
{
    private $url = 'http://site-marombas.herokuapp.com/';
    private $guzzle;
    private $maxPage;

    public function __construct()
    {
        $this->maxPage = 1;
        $this->guzzle = new GuzzleClient();
    }

    public function exec()
    {
        $this->getInitialPage();

        return true;
    }

    private function getInitialPage(int $numPage = 1)
    {
        $page = $this->guzzle->request('GET', $this->url . '?page=' . $numPage);
        $page = StringHelper::clearPageContent($page->getBody()->getContents());

        $token = $this->getToken($page);

        $this->getAthletes($token);

        if ($numPage <= $this->maxPage) {
            $numPage += 1;
            $this->getInitialPage($numPage);
        }
    }

    private function getMaxPage(string $page)
    {
        $maxPage = StringHelper::doRegex($page, '/<ul[\w\W]+?<\/ul>/i');
        $maxPage = $maxPage[0][0];

        $maxPage = StringHelper::doRegex($maxPage, '/<a[\w\W]+?>([\d]+)<\/a>/i');
        $this->maxPage = end($maxPage[1]);
    }

    private function getToken(string $page)
    {
        $token = StringHelper::doRegex($page, '/name="_token"[\s]+?value="([\w\W]+?)"/i');

        return $token[1][0] ?? '';
    }

    private function getAthletes(string $token)
    {
        $data = [
            'form_params' => [
                '_token' => $token,
                'gender' => 'Male'
            ],
        ];

        $page = $this->guzzle->request('POST', $this->url, $data)->getBody()->getContents();
        $page = StringHelper::clearPageContent($page);

        $this->getMaxPage($page);
        $this->getAthletesTable($page);
    }

    private function getAthletesTable(string $page)
    {
        $table = StringHelper::doRegex($page, '/<tbody[\w\W]+?<\/tbody>/i');
        $table = $table[0][0];

        $athletes = StringHelper::doRegex($table, '/<tr[\w\W]+?<td[\w\W]+?<\/tr>/i');
        $athletes = $athletes[0];

        $this->storeAthletes($athletes);
    }

    private function storeAthletes(array $athletes)
    {
        foreach ($athletes as $athlete) {
            dispatch(new AthleteJob($this->getData($athlete)));
        }
    }

    private function getData(string $item)
    {
        $data = StringHelper::doRegex($item, '/<td>([\w\W]+?)<\/td>/i');

        return $data[1];
    }
}
