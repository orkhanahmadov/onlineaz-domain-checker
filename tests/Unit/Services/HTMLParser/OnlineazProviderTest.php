<?php

namespace Tests\Unit\Services\HTMLParser;

use Tests\TestCase;
use App\Services\HTMLParser\OnlineazProvider;

class OnlineazProviderTest extends TestCase
{
    /**
     * @dataProvider domainsProvider
     * @param string $domain
     * @param string $name
     * @param string $tld
     */
    public function test_nameAndTld_method_returns_array_of_name_and_tld_of_given_domain(string $domain, string $name, string $tld)
    {
        $provider = new OnlineazProvider;

        $result = $provider->nameAndTld($domain);

        $this->assertEquals($name, $result['name']);
        $this->assertEquals($tld, $result['tld']);
    }

    public function domainsProvider()
    {
        return [
            ['domain1', 'domain1', 'az'],
            ['domain2.az', 'domain2', 'az'],
            ['domain3.org.az', 'domain3', 'org.az'],
        ];
    }
}
