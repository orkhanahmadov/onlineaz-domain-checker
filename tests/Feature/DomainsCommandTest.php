<?php

namespace Tests\Feature;

use Dotenv\Dotenv;
use Tests\TestCase;

class DomainsCommandTest extends TestCase
{
    public function test_sets_domains_to_env()
    {
        $this->assertEquals('', env('DOMAINS'));

        $this->artisan('domains')
            ->expectsQuestion(
                'Please add a domain. You can add multiple domains by separating with empty space',
                'domain1 domain2.az domain3.org.az'
            )
            ->assertExitCode(0);
        Dotenv::create(base_path())->overload();

        $domains = explode(',', env('DOMAINS'));

        $this->assertEquals('domain1.az', $domains[0]);
        $this->assertEquals('domain2.az', $domains[1]);
        $this->assertEquals('domain3.org.az', $domains[2]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        setEnvironmentValues('DOMAINS', '');
        Dotenv::create(base_path())->overload();
    }
}
