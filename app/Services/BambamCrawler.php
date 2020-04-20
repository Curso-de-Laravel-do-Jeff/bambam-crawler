<?php

namespace App\Services;

use App\Helpers\StringHelper;
use App\Interfaces\CrawlerInterface;

class BambamCrawler implements CrawlerInterface
{
    private $url = 'http://site-marombas.herokuapp.com/';
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new GuzzleClient();
    }

    public function exec()
    {
        dump("Tá saindo da jaula o monstro!");

        $token = $this->getInitialPage();
        $this->getAthletes($token);

        return true;
    }

    private function getInitialPage()
    {
        dump("Oh o home aí pô!");

        $page = $this->guzzle->request('GET', $this->url);
        $page = StringHelper::clearPageContent($page->getBody()->getContents());

        return $this->getToken($page);
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
            dump("Negatva Bambam");
            dump($athlete);
        }
    }
}
