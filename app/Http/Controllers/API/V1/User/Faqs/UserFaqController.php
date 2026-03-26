<?php

namespace App\Http\Controllers\API\V1\User\Faqs;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Services\API\V1\User\Faqs\UserFaqService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserFaqController extends Controller
{
    public function __construct(protected UserFaqService $faqService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $per_page = (int) ($request->per_page ?? 25);

            $data = $this->faqService->index($per_page);

            return Helper::jsonResponse(true, 'Faqs fetched successfully', 200, $data,true);
        } catch (Exception $e) {
            Log::error('UserFaqController::index ' . $e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch faqs', 500);
        }
    }

    public function categories(): JsonResponse
    {
        try {
            $categories = $this->faqService->getAllCategories();
            return Helper::jsonResponse(true, 'Categories fetched successfully', 200, $categories);
        } catch (Exception $e) {
            Log::error('UserFaqController::categories ' . $e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch category', 500);
        }
    }

    public function showCategory(string $id): JsonResponse
    {
        try {
            $category = $this->faqService->getCategoryById($id);

            return Helper::jsonResponse(true, 'Faqs fetched successfully', 200, $category);
        } catch (Exception $e) {
            Log::error('UserFaqController::byType ' . $e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch faqs', 500);
        }
    }
}

