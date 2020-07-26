<?php

namespace Tests\Feature;

use App\Mail\DomainsMail;
use App\Services\HTMLParser\Contracts\Provider;
use App\Services\HTMLParser\FakeProvider;
use App\Services\HTMLParser\OnlineazProvider;
use Dotenv\Dotenv;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckCommandTest extends TestCase
{
    public function test_shows_error_message_if_domains_does_not_exist()
    {
        setEnvironmentValues('MAIL_TO', 'email@example.com');
        setEnvironmentValues('MAIL_USERNAME', 'username');
        setEnvironmentValues('MAIL_PASSWORD', 'password');
        Dotenv::create(base_path())->overload();

        $this->artisan('check')
            ->expectsOutput('Domain list is empty. Please run "domains" command to add domain names you wish to be checked.')
            ->assertExitCode(0);
    }

    public function test_shows_error_message_if_email_setup_does_not_exist()
    {
        setEnvironmentValues('DOMAINS', 'whatever');
        Dotenv::create(base_path())->overload();

        $this->artisan('check')
            ->expectsOutput('Email configuration is missing. Please run "setup" command to configure email.')
            ->assertExitCode(0);
    }

    public function test_checks_domains_and_sends_email_about_availability()
    {
        $this->app->instance(Provider::class, new FakeProvider('Congratulation'));
        setEnvironmentValues('DOMAINS', 'domain1.az,domain2.org.az');
        setEnvironmentValues('MAIL_TO', 'email@example.com');
        setEnvironmentValues('MAIL_USERNAME', 'username');
        setEnvironmentValues('MAIL_PASSWORD', 'password');
        Dotenv::create(base_path())->overload();
        Mail::fake();

        $this->artisan('check')->assertExitCode(0);

        Mail::assertSent(DomainsMail::class, function (DomainsMail $mail) {
            return $mail->hasTo('email@example.com')
                && count($mail->domains) === 2
                && $mail->domains[0] === 'domain1.az'
                && $mail->domains[1] === 'domain2.org.az';
        });
    }

    public function test_wont_send_email_if_domains_are_not_free()
    {
        $this->app->instance(Provider::class, new FakeProvider());
        setEnvironmentValues('DOMAINS', 'online.az');
        setEnvironmentValues('MAIL_TO', 'email@example.com');
        setEnvironmentValues('MAIL_USERNAME', 'username');
        setEnvironmentValues('MAIL_PASSWORD', 'password');
        Dotenv::create(base_path())->overload();
        Mail::fake();

        $this->artisan('check')->assertExitCode(0);

        Mail::assertNotSent(DomainsMail::class);
    }

    /**
     * @group integration
     */
    public function test_onlineaz_integration()
    {
        $this->app->instance(Provider::class, new OnlineazProvider());
        setEnvironmentValues('DOMAINS', 'online.az,some-free-domain.org.az');
        setEnvironmentValues('MAIL_TO', 'email@example.com');
        setEnvironmentValues('MAIL_USERNAME', 'username');
        setEnvironmentValues('MAIL_PASSWORD', 'password');
        Dotenv::create(base_path())->overload();
        Mail::fake();

        $this->artisan('check')->assertExitCode(0);

        Mail::assertSent(DomainsMail::class, function (DomainsMail $mail) {
            return $mail->domains[0] === 'some-free-domain.org.az';
        });
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        setEnvironmentValues('DOMAINS', '');
        setEnvironmentValues('MAIL_TO', '');
        setEnvironmentValues('MAIL_USERNAME', '');
        setEnvironmentValues('MAIL_PASSWORD', '');
        Dotenv::create(base_path())->overload();
    }
}
