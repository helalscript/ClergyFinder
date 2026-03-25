<?php

namespace App\Services\API\V1\Newsletter;

use App\Models\Newsletter;

class NewsletterService
{
    /**
     * Save newsletter subscription. If email already exists, update ip.
     */
    public function subscribe(string $email, ?string $ip): Newsletter
    {
        return Newsletter::updateOrCreate(
            ['email' => $email],
            [
                'ip' => $ip,
            ]
        );
    }
}

