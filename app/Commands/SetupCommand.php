<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class SetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Configures application';

    /**
     * @var array
     */
    private $setup = [
        'MAIL_TO',
        'MAIL_FROM_ADDRESS',
        'MAIL_HOST',
        'MAIL_PORT',
        'MAIL_USERNAME',
        'MAIL_PASSWORD',
        'MAIL_ENCRYPTION',
    ];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        do {
            $this->setup['MAIL_TO'] = $this->ask('Email address where you want to receive notifications about free domains', env('MAIL_TO'));
        } while (! $this->setup['MAIL_TO']);

        do {
            $this->setup['MAIL_FROM_ADDRESS'] = $this->ask('Email address where you want to receive notifications from', env('MAIL_FROM_ADDRESS'));
        } while (! $this->setup['MAIL_FROM_ADDRESS']);

        do {
            $this->setup['MAIL_HOST'] = $this->ask('SMTP host', env('MAIL_HOST'));
        } while (! $this->setup['MAIL_HOST']);

        do {
            $this->setup['MAIL_PORT'] = $this->ask('SMTP port', env('MAIL_PORT'));
        } while (! $this->setup['MAIL_PORT']);

        do {
            $this->setup['MAIL_USERNAME'] = $this->ask('SMTP username', env('MAIL_USERNAME'));
        } while (! $this->setup['MAIL_USERNAME']);

        do {
            $this->setup['MAIL_PASSWORD'] = $this->secret('SMTP password');
        } while (! $this->setup['MAIL_PASSWORD']);

        $this->setup['MAIL_ENCRYPTION'] = $this->askWithCompletion('SMTP encryption (tls, ssl)', ['tls', 'ssl'], env('MAIL_ENCRYPTION'));

        $this->task('Saving configuration...', function () {
            foreach ($this->setup as $key => $value) {
                setEnvironmentValues($key, $value);
            }

            return true;
        });
    }
}
