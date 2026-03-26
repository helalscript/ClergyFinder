<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomePageController extends Controller
{
    // ================= Hero Section (title, sub_title, button_text) =================
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::HeroSection->value)
            ->first();
        return view('backend.layouts.cms.home_page.hero_section.create', compact('data'));
    }

    public function updateHeroSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
        ]);
        try {
              CMS::updateOrCreate(
                ['page' => Page::HomePage->value, 'section' => Section::HeroSection->value],
                $validatedData
            );
            return redirect()->route('cms.home_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateHeroSection " . $e->getMessage());
            return redirect()->route('cms.home_page.hero_section.create')->with('error', 'Hero Section Not Updated');
        }
    }

    // ================= Meet Our Clergy Section (title, sub_title, button_text) =================
    public function getMeetOurClergy()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::MeetOurClergySection->value)
            ->first();
        return view('backend.layouts.cms.home_page.meet_our_clergy.create', compact('data'));
    }

    public function updateMeetOurClergy(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
        ]);
        try {
            CMS::updateOrCreate(
                ['page' => Page::HomePage->value, 'section' => Section::MeetOurClergySection->value],
                $validatedData
            );
            return redirect()->route('cms.home_page.meet_our_clergy.create')->with('success', 'Meet Our Clergy Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateMeetOurClergy " . $e->getMessage());
            return redirect()->route('cms.home_page.meet_our_clergy.create')->with('error', 'Meet Our Clergy Not Updated');
        }
    }

    // ================= Spiritual Journey Section (title, description, button_text) =================
    public function getSpiritualJourney()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::SpiritualJourneySection->value)
            ->first();
        return view('backend.layouts.cms.home_page.spiritual_journey.create', compact('data'));
    }

    public function updateSpiritualJourney(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
        ]);
        try {
            CMS::updateOrCreate(
                ['page' => Page::HomePage->value, 'section' => Section::SpiritualJourneySection->value],
                $validatedData
            );
            return redirect()->route('cms.home_page.spiritual_journey.create')->with('success', 'Spiritual Journey Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateSpiritualJourney " . $e->getMessage());
            return redirect()->route('cms.home_page.spiritual_journey.create')->with('error', 'Spiritual Journey Not Updated');
        }
    }
}
