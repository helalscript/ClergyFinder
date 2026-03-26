<?php

namespace App\Mail;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LiveSessionScheduledToSeeker extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest
    ) {}

    public function build()
    {
        $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', url('/')));
        return $this->subject('Your Live Session Has Been Scheduled')
            ->view('mail.live-session-scheduled-to-seeker')
            ->with(['frontendUrl' => rtrim($frontendUrl, '/')]);
    }
}
