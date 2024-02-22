<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipient;
    protected $subject;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     */
    public function __construct($recipient, $subject, $message)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function handle(): void
    {
        try {
            $user = User::where('email', $this->recipient)->first();

            if ($user) {
                $user->notify(new EmailNotification($this->subject, $this->message));
            } else {
                Log::error('Failed to send email: User not found');
            }

            Log::info('Email sent successfully to ' . $this->recipient);
        } catch (\Exception $e) {
            Log::error('Failed to send email to ' . $this->recipient . ': ' . $e->getMessage());
        }
    }
}
