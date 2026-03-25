<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\Newsletter\NewsletterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsletterController extends Controller
{
    public function __construct(protected NewsletterService $newsletterService)
    {
    }

    public function subscribe(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email:rfc,dns|max:255',
            ]);

            $ip = $request->ip(); // nullable if not available.
            $newsletter = $this->newsletterService->subscribe($validated['email'], $ip);

            return Helper::jsonResponse(true, 'Subscribed successfully', 200, [
                'email' => $newsletter->email,
                'ip' => $newsletter->ip,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; // Let global exception handler return 422 properly.
        } catch (\Exception $e) {
            Log::error('NewsletterController::subscribe ' . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to subscribe', 500);
        }
    }
}

