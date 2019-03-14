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
    private $setup = [];

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

        $this->setup['MAIL_ENCRYPTION'] = $this->ask('SMTP encryption', env('MAIL_ENCRYPTION'));

        $this->task('Saving configuration...', function () {
            foreach ($this->setup as $key => $value) {
                setEnvironmentValues($key, $value);
            }

            return true;
        });
    }
}
