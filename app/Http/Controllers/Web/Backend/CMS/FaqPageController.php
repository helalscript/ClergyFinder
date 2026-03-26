<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FaqPageController extends Controller
{
    // ================= Hero Section (title, description) =================
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::FaqPage->value)
            ->where('section', Section::FaqPageHeroSection->value)
            ->first();

        return view('backend.layouts.cms.faq_page.hero_section.create', compact('data'));
    }

    public function updateHeroSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            CMS::updateOrCreate(
                ['page' => Page::FaqPage->value, 'section' => Section::FaqPageHeroSection->value],
                array_merge($validatedData, ['status' => 'active'])
            );

            return redirect()->route('cms.faq_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error('FaqPageController::updateHeroSection ' . $e->getMessage());
            return redirect()->route('cms.faq_page.hero_section.create')->with('error', 'Hero Section Not Updated');
        }
    }
}

