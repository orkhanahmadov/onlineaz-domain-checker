<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    public $domains;

    /**
     * Create a new message instance.
     *
     * @param array $domains
     */
    public function __construct(array $domains)
    {
        $this->domains = $domains;
    }

    /**
     * Build the message.
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function build()
    {
        return $this
            ->subject('Some of domains you listed are free')
            ->markdown('emails.domains');
    }
}
