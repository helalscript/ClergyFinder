<?php

namespace App\Mail;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PrayerReplyToSeeker extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PrayerRequest $prayerRequest
    ) {}

    public function build()
    {
        $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', url('/')));
        return $this->subject('Reply to Your Prayer Request')
            ->view('mail.prayer-reply-to-seeker')
            ->with(['frontendUrl' => rtrim($frontendUrl, '/')]);
    }
}
