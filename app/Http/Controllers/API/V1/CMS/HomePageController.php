<?php

namespace App\Http\Controllers\API\V1\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use App\Models\DynamicPage;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    public function getSystemInfo(Request $request): JsonResponse
    {
        try {
            $data = SystemSetting::select('id', 'system_name', 'logo', 'favicon', 'copyright_text', 'address', 'company_open_hour', 'description', 'contact_number', 'address', 'email')->first();
            return Helper::jsonResponse(true, 'System data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getSystemInfo" . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve System', 403);
        }
    }

    public function getDynamicPages(Request $request): JsonResponse
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $dynamicPages = DynamicPage::where('status', 'active')->select('id', 'page_title', 'page_slug')->paginate($per_page);
            if (!$dynamicPages) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Dynamic pages retrieved successfully.', 200, $dynamicPages, true);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to retrieve Dynamic pages data.', 403);
        }
    }

    public function showDaynamicPage($page_slug): JsonResponse
    {
        try {
            $page = DynamicPage::where('page_slug', $page_slug)->where('status', 'active')->firstOrFail();
            if (!$page) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Dynamic page retrieved successfully.', 200, $page);
        } catch (Exception $e) {
            return Helper::jsonErrorResponse('Failed to retrieve Dynamic page data.', 403);
        }
    }

    public function getSocialLinks(Request $request): JsonResponse
    {
        try {
            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $data = CMS::select('id', 'title', 'image', 'link_url')->where('page', Page::HomePage)->where('section', Section::SocialLinkContainer)->where('status', 'active')->paginate($per_page);
            return Helper::jsonResponse(true, 'Social links data fetched successfully', 200, $data, true);
        } catch (Exception $e) {
            Log::error("HomePageController::getSocialLinks" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // Hero: title, sub_title, button_text
    public function getHeroSection(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HomePage)
                ->where('section', Section::HeroSection)
                ->where('status', 'active')
                ->select('id', 'title', 'sub_title', 'button_text')
                ->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHeroSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // Counter: content(title), items(count_number, sub_title)
    public function getCounterSection(): JsonResponse
    {
        try {
            $section = CMS::where('page', Page::HomePage)
                ->where('section', Section::CounterSection)
                ->where('status', 'active')
                ->select('id', 'title')
                ->first();

            $items = CMS::where('page', Page::HomePage)
                ->where('section', Section::CounterSectionContent)
                ->where('status', 'active')
                ->select('id', 'title as count_number', 'sub_title')
                ->orderBy('id')
                ->get();

            $data = [
                'content' => ['title' => $section?->title ?? ''],
                'items' => $items,
            ];
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getCounterSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // MeetOurClergy: title, sub_title, button_text
    public function getMeetOurClergySection(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HomePage)
                ->where('section', Section::MeetOurClergySection)
                ->where('status', 'active')
                ->select('id', 'title', 'sub_title', 'button_text')
                ->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getMeetOurClergySection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // HowItWorks: content(title), items(image, title, description)
    public function getHowItWorksSection(): JsonResponse
    {
        try {
            $section = CMS::where('page', Page::HomePage)
                ->where('section', Section::HomeHowItWorksSection)
                ->where('status', 'active')
                ->select('id', 'title')
                ->first();

            $items = CMS::where('page', Page::HomePage)
                ->where('section', Section::HomeHowItWorksSectionContent)
                ->where('status', 'active')
                ->select('id', 'image', 'title', 'description')
                ->orderBy('id')
                ->get();

            $data = [
                'content' => ['title' => $section?->title ?? ''],
                'items' => $items,
            ];
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHowItWorksSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // Review: content(title, description), items(image, title, sub_title, description, number/stars)
    public function getReviewSection(): JsonResponse
    {
        try {
            $section = CMS::where('page', Page::HomePage)
                ->where('section', Section::ReviewSection)
                ->where('status', 'active')
                ->select('id', 'title', 'description')
                ->first();

            $items = CMS::where('page', Page::HomePage)
                ->where('section', Section::ReviewSectionContent)
                ->where('status', 'active')
                ->select('id', 'image', 'title', 'sub_title', 'description', 'tags')
                ->orderBy('id')
                ->get()
                ->map(function ($item) {
                    $tags = is_array($item->tags) ? $item->tags : (json_decode($item->tags, true) ?? []);
                    return [
                        'id' => $item->id,
                        'image' => $item->image,
                        'title' => $item->title,
                        'sub_title' => $item->sub_title,
                        'description' => $item->description,
                        'stars' => $tags['stars'] ?? null,
                    ];
                });

            $data = [
                'content' => [
                    'title' => $section?->title ?? '',
                    'description' => $section?->description ?? '',
                ],
                'items' => $items,
            ];
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getReviewSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    // Spiritual Journey: title, description, button_text
    public function getSpiritualJourneySection(): JsonResponse
    {
        try {
            $data = CMS::where('page', Page::HomePage)
                ->where('section', Section::SpiritualJourneySection)
                ->where('status', 'active')
                ->select('id', 'title', 'description', 'button_text')
                ->first();
            if (!$data) {
                return Helper::jsonResponse(true, 'No data found', 200, []);
            }
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getSpiritualJourneySection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
}
