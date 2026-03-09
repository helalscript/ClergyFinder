<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Artisan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class SystemSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = SystemSetting::latest('id')->first();
        return view('backend.layouts.settings.index', compact('settings'));
    }
    /**
     * Display a listing of the resource.
     */
    public function mailSettingGet()
    {
        $settings = [
            'mail_mailer' => env('MAIL_MAILER', ''),
            'mail_host' => env('MAIL_HOST', ''),
            'mail_port' => env('MAIL_PORT', ''),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', ''),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
        ];

        return view('backend.layouts.settings.mail_settings', compact('settings'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validateDta = $request->validate([
            'title' => 'required|string|max:100',
            'system_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255',
            'contact_number' => 'nullable|string|max:20',
            'company_open_hour' => 'nullable|string|max:255',
            'copyright_text' => 'required|string|max:255',
            'logo' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
            'favicon' => 'nullable|mimes:jpeg,jpg,png,ico,svg',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);
        // dd($validateDta);
        $setting = SystemSetting::firstOrNew();
        $setting->title = $request->title;
        $setting->system_name = $request->system_name;
        $setting->email = $request->email;
        $setting->contact_number = $request->contact_number??null;
        $setting->company_open_hour = $request->company_open_hour??null;
        $setting->copyright_text = $request->copyright_text;
        $setting->address = $request->address;
        $setting->description = $request->description;

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Helper::fileDelete(public_path($setting->logo));
            }
            $setting->logo = Helper::fileUpload($request->file('logo'), 'logos', time() . '_' . getFileName($request->file('logo')));
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Helper::fileDelete(public_path($setting->favicon));
            }
            $setting->favicon = Helper::fileUpload($request->file('favicon'), 'favicons', time() . '_' . getFileName($request->file('favicon')));
        }
        $setting->save();

        flash()->success("System Setting Updated Successfully");
        return back();
    }

    /**
     * Update mail settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function mailSettingUpdate(Request $request): RedirectResponse
    {
        $request->validate([
            'mail_mailer' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|string',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            // 'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|string',
        ]);

        try {
            $envContent = File::get(base_path('.env'));
            $lineBreak = "\n";
            $envContent = preg_replace([
                '/MAIL_MAILER=(.*)\s*/',
                '/MAIL_HOST=(.*)\s*/',
                '/MAIL_PORT=(.*)\s*/',
                '/MAIL_USERNAME=(.*)\s*/',
                '/MAIL_PASSWORD=(.*)\s*/',
                '/MAIL_ENCRYPTION=(.*)\s*/',
                '/MAIL_FROM_ADDRESS=(.*)\s*/',
            ], [
                'MAIL_MAILER=' . $request->mail_mailer . $lineBreak,
                'MAIL_HOST=' . $request->mail_host . $lineBreak,
                'MAIL_PORT=' . $request->mail_port . $lineBreak,
                'MAIL_USERNAME=' . $request->mail_username . $lineBreak,
                'MAIL_PASSWORD=' . $request->mail_password . $lineBreak,
                'MAIL_ENCRYPTION=' . $request->mail_encryption . $lineBreak,
                'MAIL_FROM_ADDRESS=' . '"' . $request->mail_from_address . '"' . $lineBreak,
            ], $envContent);

            File::put(base_path('.env'), $envContent);
            flash()->success("Mail Setting Updated Successfully");
            return back();
        } catch (Exception) {
            flash()->success("Failed Updated ");
            return back();
        }
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');
        flash()->success("Cache Clear Successfully");
        return back();
    }
    public function clearView()
    {
        Artisan::call('view:clear');
        flash()->success("View Clear Successfully");
        return back();
    }

    public function socialConfigGet()
    {
        return view('backend.layouts.settings.social_login_configuration');
    }
    public function paymentSettingGet()
    {
        return view('backend.layouts.settings.payment_configuration');
    }

    public function paymentSettingUpdate(Request $request)
    {
        try {
            if (auth()->user()->role === 'admin') {
                $request->validate([
                    'stripe_key' => 'required|string',
                    'stripe_secret' => 'required|string',
                    'stripe_webhook_secret' => 'required|string',
                ]);
    
                $envPath = base_path('.env');
                $envContent = File::get($envPath);
    
                $lineBreak = "\n";
                $stripePublicKey = trim($request->stripe_key);
                $stripeSecretKey = trim($request->stripe_secret);
                $stripeWebhookSecret = trim($request->stripe_webhook_secret);
    
                $envContent = preg_replace([
                    '/^STRIPE_PUBLIC_KEY=.*$/m',
                    '/^STRIPE_SECRET_KEY=.*$/m',
                    '/^STRIPE_WEBHOOK_SECRET=.*$/m',
                ], [
                    'STRIPE_PUBLIC_KEY="' . $stripePublicKey . '"',
                    'STRIPE_SECRET_KEY="' . $stripeSecretKey . '"',
                    'STRIPE_WEBHOOK_SECRET="' . $stripeWebhookSecret . '"',
                ], $envContent);
    
                if ($envContent !== null) {
                    File::put($envPath, $envContent);
                }
    
                flash()->success('Stripe settings updated successfully.');
            } else {
                flash()->error('Unauthorized action.');
            }
        } catch (\Exception $e) {
            \Log::error('Payment Setting Update Error: ' . $e->getMessage());
            flash()->error('Something went wrong while updating settings.');
        }
    
        return redirect()->back();
    }
    



    /**
     * Update social app Settings.
     *
     * This method sanitizes and validates input values for social app Settings (Google, Facebook, Apple) and updates the .env file accordingly.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function socialAppUpdate(Request $request)
    {
        // Sanitize input values
        $request->merge([
            'google_client_id' => preg_replace('/\s+/', '', $request->google_client_id),
            'google_client_secret' => preg_replace('/\s+/', '', $request->google_client_secret),
            'google_redirect_uri' => preg_replace('/\s+/', '', $request->google_redirect_uri),
            // 'facebook_client_id' => preg_replace('/\s+/', '', $request->facebook_client_id),
            // 'facebook_client_secret' => preg_replace('/\s+/', '', $request->facebook_client_secret),
            // 'facebook_redirect_uri' => preg_replace('/\s+/', '', $request->facebook_redirect_uri),
            // 'apple_client_id' => preg_replace('/\s+/', '', $request->apple_client_id),
            // 'apple_client_secret' => preg_replace('/\s+/', '', $request->apple_client_secret),
            // 'apple_redirect_uri' => preg_replace('/\s+/', '', $request->apple_redirect_uri),
        ]);

        $request->validate([
            'google_client_id' => 'required|string',
            'google_client_secret' => 'required|string',
            'google_redirect_uri' => 'required|string',
            // 'facebook_client_id' => 'required|string',
            // 'facebook_client_secret' => 'required|string',
            // 'facebook_redirect_uri' => 'required|string',
            // 'apple_client_id' => 'required|string',
            // 'apple_client_secret' => 'required|string',
            // 'apple_redirect_uri' => 'required|string',
        ]);

        $envContent = File::get(base_path('.env'));
        $lineBreak = "\n";
        $envContent = preg_replace([
            '/GOOGLE_CLIENT_ID=(.*)\s/',
            '/GOOGLE_CLIENT_SECRET=(.*)\s/',
            '/GOOGLE_REDIRECT_URI=(.*)\s/',
            // '/FACEBOOK_CLIENT_ID=(.*)\s/',
            // '/FACEBOOK_CLIENT_SECRET=(.*)\s/',
            // '/FACEBOOK_REDIRECT_URI=(.*)\s/',
            // '/APPLE_CLIENT_ID=(.*)\s/',
            // '/APPLE_CLIENT_SECRET=(.*)\s/',
            // '/APPLE_REDIRECT_URI=(.*)\s/',
        ], [
            'GOOGLE_CLIENT_ID=' . $request->google_client_id . $lineBreak,
            'GOOGLE_CLIENT_SECRET=' . $request->google_client_secret . $lineBreak,
            'GOOGLE_REDIRECT_URI=' . $request->google_redirect_uri . $lineBreak,
            // 'FACEBOOK_CLIENT_ID=' . $request->facebook_client_id . $lineBreak,
            // 'FACEBOOK_CLIENT_SECRET=' . $request->facebook_client_secret . $lineBreak,
            // 'FACEBOOK_REDIRECT_URI=' . $request->facebook_redirect_uri . $lineBreak,
            // 'APPLE_CLIENT_ID=' . $request->apple_client_id . $lineBreak,
            // 'APPLE_CLIENT_SECRET=' . $request->apple_client_secret . $lineBreak,
            // 'APPLE_REDIRECT_URI=' . $request->apple_redirect_uri . $lineBreak,
        ], $envContent);

        if ($envContent !== null) {
            File::put(base_path('.env'), $envContent);
        }
        flash()->success('Social Setting Update successfully.');
        return redirect()->back();

    }

}
