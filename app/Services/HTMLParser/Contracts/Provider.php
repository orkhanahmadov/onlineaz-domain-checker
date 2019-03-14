<?php

namespace App\Services\HTMLParser\Contracts;

interface Provider
{
    public function download(string $url);

    public function parse(string $selector): string;

    public function checkDomain(string $domain): bool;
}
