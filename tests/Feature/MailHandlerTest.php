<?php

namespace Tests\Feature;

use App\Http\Classes\MailHandler;
use App\Mail\MailClientCreated;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailHandlerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test sending an email
     *
     * @return void
     */
    public function testSendEmail()
    {
        // Prevents emails from actually being sent
        Mail::fake();

        // Create a test client
        $clientData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '',
            'comments' => $this->faker->sentence(7),
        ];
        $client = new Client();
        $client->name = $clientData['name'];
        $client->email = $clientData['email'];
        $client->phone = $clientData['phone'];
        $client->comments = $clientData['comments'];

        $mailHandler = new MailHandler($client);
        $mailHandler->sendEmail();

        // Verify that the email has the correct recipient and associated client
        Mail::assertSent(MailClientCreated::class, function ($mail) use ($client) {
            return $mail->hasTo(env('MAIL_SUPPORT_ADDRESS'))
                && $mail->client->id === $client->id;
        });
    }
}
