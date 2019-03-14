<?php

namespace App\Services\HTMLParser;

class FakeProvider implements Contracts\Provider
{
    /**
     * @var string
     */
    private $html;

    /**
     * FakeHTMLParser constructor.
     * @param string $html
     */
    public function __construct(string $html = '')
    {
        $this->html = $html;
    }

    public function download(string $url)
    {
        return $this;
    }

    public function parse(string $selector): string
    {
        $this->download('');

        return 'Congratulation';
    }

    public function checkDomain(string $domain): bool
    {
        $this->parse('');

        return strpos($this->html, 'Congratulation') !== false;
    }
}
