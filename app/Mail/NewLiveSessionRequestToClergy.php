<?php

namespace App\Mail;

use App\Models\LiveSessionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewLiveSessionRequestToClergy extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LiveSessionRequest $liveSessionRequest
    ) {}

    public function build()
    {
        $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', url('/')));
        return $this->subject('New Live Session Request')
            ->view('mail.new-live-session-request-to-clergy')
            ->with(['frontendUrl' => rtrim($frontendUrl, '/')]);
    }
}
