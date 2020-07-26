<?php

namespace App\Commands;

use App\Mail\DomainsMail;
use App\Services\HTMLParser\Contracts\Provider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Mail;
use LaravelZero\Framework\Commands\Command;

class CheckCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'check {domains?} {--email=}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Checks availability of domains on online.az';

    /**
     * @var array
     */
    private $freeDomains = [];

    /**
     * Execute the console command.
     *
     * @param \App\Services\HTMLParser\OnlineazProvider|Provider $provider
     * @return mixed
     */
    public function handle(Provider $provider)
    {
        if ($error = $this->hasError()) {
            return $this->error($error);
        }

        foreach (explode(',', env('DOMAINS')) as $domain) {
            $this->task("Checking {$domain} availability...", function () use ($domain, $provider) {
                if ($provider->checkDomain($domain)) {
                    array_push($this->freeDomains, $domain);
                }

                return true;
            });
        }

        if (count($this->freeDomains) > 0) {
            $this->info(implode(', ', $this->freeDomains).' domains are available!');
            Mail::to(env('MAIL_TO'))->send(new DomainsMail($this->freeDomains));
        }
    }

    /**
     * @return bool|string
     */
    private function hasError()
    {
        if (blank(env('MAIL_TO')) ||
            blank(env('MAIL_HOST')) ||
            blank(env('MAIL_PORT')) ||
            blank(env('MAIL_USERNAME')) ||
            blank(env('MAIL_PASSWORD')) ||
            blank(env('MAIL_ENCRYPTION'))) {
            return 'Email configuration is missing. Please run "setup" command to configure email.';
        } elseif (! filled(env('DOMAINS'))) {
            return 'Domain list is empty. Please run "domains" command to add domain names you wish to be checked.';
        }

        return false;
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->hourly();
    }
}
