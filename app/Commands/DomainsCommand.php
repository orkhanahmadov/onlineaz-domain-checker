<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;

class DomainsCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'domains';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generates domain list';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        do {
            $list = $this->ask('Please add a domain. You can add multiple domains by separating with empty space', env('DOMAINS'));
        } while (! $list);

        $domains = $this->parseDomains($list);
        $this->task("Adding {$domains} ...", function () use ($domains) {
            setEnvironmentValues('DOMAINS', $domains);

            return true;
        });
    }

    public function parseDomains(string $input): string
    {
        $parsedDomains = array_filter(explode(' ', $input));

        foreach ($parsedDomains as $key => $domain) {
            $domainParts = explode('.', $domain);
            $parsedDomains[$key] = isset($domainParts[1]) ? $domain : $domainParts[0].'.az';
        }

        return implode(',', $parsedDomains);
    }
}
