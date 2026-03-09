<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;

class ContactUsPageController extends Controller
{
    // ================= MAIN CONTACT US PAGE =================
    public function index()
    {
        $data = CMS::where('page', Page::ContactUsPage->value)
            ->where('section', Section::Main->value)
            ->first();

        return view('backend.layouts.cms.contact_us_page.index', compact('data'));
    }

    public function update(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        try {
            $data = CMS::where('page', Page::ContactUsPage->value)
                ->where('section', Section::Main->value)
                ->first();

            $validatedData = $this->handleFileUpload($request, $data, 'contact_us_page');

            CMS::updateOrCreate(
                ['page' => Page::ContactUsPage->value, 'section' => Section::Main->value],
                $validatedData
            );

            return redirect()->route('cms.contact_us_page.index')
                ->with('success', 'Contact Us Page Updated Successfully');
        } catch (Exception $e) {
            Log::error("ContactUsPageController::update - " . $e->getMessage());
            return redirect()->route('cms.contact_us_page.index')
                ->with('error', 'Contact Us Page Not Updated');
        }
    }

    // ================= CONTACT FORM SECTION =================
    public function formIndex()
    {
        $data = CMS::where('page', Page::ContactUsPage->value)
            ->where('section', Section::Form->value)
            ->first();

        return view('backend.layouts.cms.contact_us_page.form.index', compact('data'));
    }

    public function formUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sub_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $data = CMS::where('page', Page::ContactUsPage->value)
                ->where('section', Section::Form->value)
                ->first();

            $validatedData = $this->handleFileUpload($request, $data, 'contact_us_page');

            CMS::updateOrCreate(
                ['page' => Page::ContactUsPage->value, 'section' => Section::Form->value],
                $validatedData
            );

            return redirect()->route('cms.contact_us_page.form.index')
                ->with('success', 'Contact Form Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("ContactUsPageController::formUpdate - " . $e->getMessage());
            return redirect()->route('cms.contact_us_page.form.index')
                ->with('error', 'Contact Form Section Not Updated');
        }
    }

    // ================= HELPER FUNCTIONS =================
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'sub_description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);
    }

    private function handleFileUpload(Request $request, $data, $folder)
    {
        $validatedData = $request->only(['title', 'sub_title', 'description', 'sub_description']);

        if ($request->hasFile('image')) {
            if ($data && $data->image && file_exists(public_path($data->image))) {
                Helper::fileDelete(public_path($data->image));
            }
            $validatedData['image'] = Helper::fileUpload(
                $request->file('image'),
                $folder,
                time() . '_' . getFileName($request->file('image'))
            );
        }

        return $validatedData;
    }
}
