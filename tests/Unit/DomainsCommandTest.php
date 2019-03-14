<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Commands\DomainsCommand;

class DomainsCommandTest extends TestCase
{
    public function test_parseDomains_method_returns_parsed_domains_as_string()
    {
        $command = new DomainsCommand;

        $result = $command->parseDomains('domain1 domain2.az  domain3.org.az');

        $this->assertEquals('domain1.az,domain2.az,domain3.org.az', $result);
    }
}
