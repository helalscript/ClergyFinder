<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Enums\Page;
use App\Enums\Section;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HowItWorkPageController extends Controller
{
    // ================= Hero Banner Section (title, description, button_text) =================
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::HowItWorkPage->value)
            ->where('section', Section::HowItWorkHeroSection->value)
            ->first();
        return view('backend.layouts.cms.how_it_work_page.hero_section.create', compact('data'));
    }

    public function updateHeroSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:255',
        ]);
        try {
            CMS::updateOrCreate(
                ['page' => Page::HowItWorkPage->value, 'section' => Section::HowItWorkHeroSection->value],
                array_merge($validatedData, ['status' => 'active'])
            );
            return redirect()->route('cms.how_it_work_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::updateHeroSection " . $e->getMessage());
            return redirect()->route('cms.how_it_work_page.hero_section.create')->with('error', 'Hero Section Not Updated');
        }
    }

    // ================= Spiritual Journey Section (title, description, button_text) =================
    public function getSpiritualJourney()
    {
        $data = CMS::where('page', Page::HowItWorkPage->value)
            ->where('section', Section::HowItWorkSpiritualJourneySection->value)
            ->first();
        return view('backend.layouts.cms.how_it_work_page.spiritual_journey.create', compact('data'));
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
                ['page' => Page::HowItWorkPage->value, 'section' => Section::HowItWorkSpiritualJourneySection->value],
                array_merge($validatedData, ['status' => 'active'])
            );
            return redirect()->route('cms.how_it_work_page.spiritual_journey.create')->with('success', 'Spiritual Journey Updated Successfully');
        } catch (Exception $e) {
            Log::error("HowItWorkPageController::updateSpiritualJourney " . $e->getMessage());
            return redirect()->route('cms.how_it_work_page.spiritual_journey.create')->with('error', 'Spiritual Journey Not Updated');
        }
    }
}
