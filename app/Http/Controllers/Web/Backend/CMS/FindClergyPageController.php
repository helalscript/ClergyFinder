<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Enums\Page;
use App\Enums\Section;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FindClergyPageController extends Controller
{
    // ================= Hero Section (title, description) =================
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::FindClergyPage->value)
            ->where('section', Section::FindClergyHeroSection->value)
            ->first();
        return view('backend.layouts.cms.find_clergy_page.hero_section.create', compact('data'));
    }

    public function updateHeroSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        try {
            CMS::updateOrCreate(
                ['page' => Page::FindClergyPage->value, 'section' => Section::FindClergyHeroSection->value],
                array_merge($validatedData, ['status' => 'active'])
            );
            return redirect()->route('cms.find_clergy_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("FindClergyPageController::updateHeroSection " . $e->getMessage());
            return redirect()->route('cms.find_clergy_page.hero_section.create')->with('error', 'Hero Section Not Updated');
        }
    }
}
