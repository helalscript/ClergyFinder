<?php

namespace App\Http\Controllers\API\V1\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OnboardingController extends Controller
{
    /**
     * Get onboarding screens (section title + list of active screens).
     *
     * @return JsonResponse
     */
    public function getOnboardingScreens(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::OnboardingPage)
                ->where('section', Section::OnboardingScreenSectionContent)
                ->where('status', 'active')
                ->select('id', 'title', 'description', 'sub_title', 'image', 'button_text')
                ->orderBy('id')
                ->get();

            return Helper::jsonResponse(true, 'Onboarding screens fetched successfully.', 200, $data);
        } catch (Exception $e) {
            Log::error("OnboardingController::getOnboardingScreens " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve onboarding screens.', 500);
        }
    }
}
