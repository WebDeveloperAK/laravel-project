<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCustomEmail;

class SendEmails extends Command
{
    protected $signature = 'emails:send';
    protected $description = 'Send an email to a specific user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $recipient = 'ahirrohit787@gmail.com';
        $details = [
            'message' => 'This is a test email sent from Laravel Artisan command.'
        ];

        Mail::to($recipient)->send(new SendCustomEmail($details));

        $this->info("Email sent to $recipient successfully!");
    }
}
