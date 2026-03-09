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

class HowItWorkPageController extends Controller
{
    // Hero: title, description, button_text
    public function getHeroSection(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkHeroSection)
                ->where('status', 'active')
                ->select('id', 'title', 'description', 'button_text')
                ->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::getHeroSection " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // WorkProgress: content(title, description), items(image, title, description)
    public function getWorkProgressSection(): JsonResponse
    {
        try {
            $section = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkWorkProgressSection)
                ->where('status', 'active')
                ->select('id', 'title', 'description')
                ->first();

            $items = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkWorkProgressSectionContent)
                ->where('status', 'active')
                ->select('id', 'image', 'title', 'description')
                ->orderBy('id')
                ->get();

            $data = [
                'content' => [
                    'title' => $section?->title ?? '',
                    'description' => $section?->description ?? '',
                ],
                'items' => $items,
            ];
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::getWorkProgressSection " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // OurValues: content(title, description), items(image, title, description)
    public function getOurValuesSection(): JsonResponse
    {
        try {
            $section = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkOurValuesSection)
                ->where('status', 'active')
                ->select('id', 'title', 'description')
                ->first();

            $items = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkOurValuesSectionContent)
                ->where('status', 'active')
                ->select('id', 'image', 'title', 'description')
                ->orderBy('id')
                ->get();

            $data = [
                'content' => [
                    'title' => $section?->title ?? '',
                    'description' => $section?->description ?? '',
                ],
                'items' => $items,
            ];
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::getOurValuesSection " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // SpiritualJourney: title, description, button_text
    public function getSpiritualJourneySection(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HowItWorkPage)
                ->where('section', Section::HowItWorkSpiritualJourneySection)
                ->where('status', 'active')
                ->select('id', 'title', 'description', 'button_text')
                ->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::getSpiritualJourneySection " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
}
