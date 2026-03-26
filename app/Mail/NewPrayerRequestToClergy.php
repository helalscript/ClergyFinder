<?php

namespace App\Mail;

use App\Models\PrayerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPrayerRequestToClergy extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public PrayerRequest $prayerRequest) 
    {
        //
    }

    public function build()
    {
        $frontendUrl = config('app.frontend_url', env('FRONTEND_URL', url('/')));
        return $this->subject('New Prayer Request')
            ->view('mail.new-prayer-request-to-clergy')
            ->with(['frontendUrl' => rtrim($frontendUrl, '/')]);
    }
}
