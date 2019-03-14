<?php

namespace Tests\Feature;

use Dotenv\Dotenv;
use Tests\TestCase;

class SetupCommandTest extends TestCase
{
    public function test_sets_email_setup_to_env()
    {
        $this->assertEquals('', env('MAIL_TO'));
        $this->assertEquals('smtp.google.com', env('MAIL_HOST'));
        $this->assertEquals(465, env('MAIL_PORT'));
        $this->assertEquals('', env('MAIL_USERNAME'));
        $this->assertEquals('', env('MAIL_PASSWORD'));
        $this->assertEquals('tls', env('MAIL_ENCRYPTION'));

        $this->artisan('setup')
            ->expectsQuestion('Email address where you want to receive notifications about free domains', 'whatever@example.com')
            ->expectsQuestion('SMTP host', 'smtp.whatever')
            ->expectsQuestion('SMTP port', 555)
            ->expectsQuestion('SMTP username', 'username')
            ->expectsQuestion('SMTP password', 'pass')
            ->expectsQuestion('SMTP encryption', 'ssl')
            ->assertExitCode(0);
        (new Dotenv(base_path()))->overload();

        $this->assertEquals('whatever@example.com', env('MAIL_TO'));
        $this->assertEquals('smtp.whatever', env('MAIL_HOST'));
        $this->assertEquals(555, env('MAIL_PORT'));
        $this->assertEquals('username', env('MAIL_USERNAME'));
        $this->assertEquals('pass', env('MAIL_PASSWORD'));
        $this->assertEquals('ssl', env('MAIL_ENCRYPTION'));
    }

    protected function tearDown()
    {
        parent::tearDown();

        setEnvironmentValues('MAIL_TO', '');
        setEnvironmentValues('MAIL_HOST', 'smtp.google.com');
        setEnvironmentValues('MAIL_PORT', 465);
        setEnvironmentValues('MAIL_USERNAME', '');
        setEnvironmentValues('MAIL_PASSWORD', '');
        setEnvironmentValues('MAIL_ENCRYPTION', 'tls');
        (new Dotenv(base_path()))->overload();
    }
}
