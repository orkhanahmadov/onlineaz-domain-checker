<?php

namespace App\Services\HTMLParser;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class OnlineazProvider implements Contracts\Provider
{
    /**
     * @var string
     */
    public $html;

    /**
     * @param string $url
     * @return $this
     * @codeCoverageIgnore
     */
    public function download(string $url)
    {
        try {
            $this->html = (new Client)->get($url)->getBody()->getContents();
        } catch (\Exception $e) {
            die("Could not connect to online.az. Do you have working internet connection? Are you using VPN?\n");
        }

        return $this;
    }

    /**
     * @param string $selector
     * @return string
     * @codeCoverageIgnore
     */
    public function parse(string $selector): string
    {
        try {
            $crawler = new Crawler($this->html);

            return $crawler->filter($selector)->text();
        } catch (\InvalidArgumentException $e) {
            die("Failed to parse page on online.az, probably they changed the HTML structure. Please create an issue here: https://github.com/orkhanahmadov/onlineaz-domain-checker/issues\n");
        }
    }

    /**
     * @param string $domain
     * @return bool
     * @codeCoverageIgnore
     */
    public function checkDomain(string $domain): bool
    {
        $parsedDomain = $this->nameAndTld($domain);

        $result = $this
            ->download("https://online.az/?r=site%2Fsearch&domain={$parsedDomain['name']}&tld=.{$parsedDomain['tld']}")
            ->parse('.alert > p');

        return strpos($result, 'Congratulation') !== false;
    }

    /**
     * @param string $domain
     * @return array
     */
    public function nameAndTld(string $domain): array
    {
        $input = explode('.', $domain);
        $name = $input[0];
        $tld = 'az';

        if (isset($input[1])) {
            $name = array_shift($input);
            $tld = implode('.', $input);
        }

        return compact('name', 'tld');
    }
}
