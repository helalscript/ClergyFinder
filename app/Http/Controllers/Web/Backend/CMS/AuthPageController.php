<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthPageController extends Controller
{
     // ================= LOGIN PAGE =================
    public function getLogin()
    {
        $data = CMS::where('page', Page::AuthPage->value)
                    ->where('section', Section::Login->value)
                    ->first();

        return view('backend.layouts.cms.auth_page.login.create', compact('data'));
    }

    public function updateLogin(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        try {
            $data = CMS::where('page', Page::AuthPage->value)
                       ->where('section', Section::Login->value)
                       ->first();

            $validatedData = $this->handleFileUpload($request, $data, 'auth_page');

            CMS::updateOrCreate(
                ['page' => Page::AuthPage->value, 'section' => Section::Login->value],
                $validatedData
            );

            return redirect()->route('cms.auth_page.login.index')
                             ->with('success', 'Login Page Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("AuthPageController::updateLogin - " . $e->getMessage());
            return redirect()->route('cms.auth_page.login.index')
                             ->with('error', 'Login Page Section Not Updated');
        }
    }

    // ================= REGISTER PAGE =================
    public function getRegister()
    {
        $data = CMS::where('page', Page::AuthPage->value)
                    ->where('section', Section::Register->value)
                    ->first();

        return view('backend.layouts.cms.auth_page.register.create', compact('data'));
    }

    public function updateRegister(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        try {
            $data = CMS::where('page', Page::AuthPage->value)
                       ->where('section', Section::Register->value)
                       ->first();

            $validatedData = $this->handleFileUpload($request, $data, 'auth_page');

            CMS::updateOrCreate(
                ['page' => Page::AuthPage->value, 'section' => Section::Register->value],
                $validatedData
            );

            return redirect()->route('cms.auth_page.register.index')
                             ->with('success', 'Register Page Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("AuthPageController::updateRegister - " . $e->getMessage());
            return redirect()->route('cms.auth_page.register.index')
                             ->with('error', 'Register Page Section Not Updated');
        }
    }

    // ================= FORGOT PASSWORD PAGE =================
    public function getForgotPassword()
    {
        $data = CMS::where('page', Page::AuthPage->value)
                    ->where('section', Section::ForgotPassword->value)
                    ->first();

        return view('backend.layouts.cms.auth_page.forgot_password.create', compact('data'));
    }

    public function updateForgotPassword(Request $request)
    {
        $validatedData = $this->validateRequest($request);

        try {
            $data = CMS::where('page', Page::AuthPage->value)
                       ->where('section', Section::ForgotPassword->value)
                       ->first();

            $validatedData = $this->handleFileUpload($request, $data, 'auth_page');

            CMS::updateOrCreate(
                ['page' => Page::AuthPage->value, 'section' => Section::ForgotPassword->value],
                $validatedData
            );

            return redirect()->route('cms.auth_page.forgot_password.index')
                             ->with('success', 'Forgot Password Page Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("AuthPageController::updateForgotPassword - " . $e->getMessage());
            return redirect()->route('cms.auth_page.forgot_password.index')
                             ->with('error', 'Forgot Password Page Section Not Updated');
        }
    }

    // ================= Helper Functions =================
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
