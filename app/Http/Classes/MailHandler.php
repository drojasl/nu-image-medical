<?php

namespace App\Http\Classes;

use Illuminate\Support\Facades\Mail;
use App\Mail\MailClientCreated;
use App\Models\Client;

class MailHandler
{
    private Client $_client;

    public function __construct(Client $client) {
        $this->_client = $client;
    }

    public function sendEmail() {
        Mail::to(env('MAIL_SUPPORT_ADDRESS'))->send(new MailClientCreated($this->_client));
    }
}