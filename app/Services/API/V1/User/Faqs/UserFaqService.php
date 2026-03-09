<?php

namespace App\Services\API\V1\User\Faqs;

use App\Models\Faq;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserFaqService
{
    protected $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    /**
     * Fetch all resources.
     *
     * @return mixed
     */
    public function index($request)
    {
        try {
            $per_page = $request->per_page ?? 25;
            $faqs = Faq::select('id', 'question', 'answer')
                ->paginate($per_page);
            return $faqs;
        } catch (Exception $e) {
            Log::error("UserFaqService::index" . $e->getMessage());
            throw $e;
        }
    }
}