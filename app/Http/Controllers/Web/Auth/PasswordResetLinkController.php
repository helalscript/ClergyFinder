<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                flash()->error('The email address is not associated with any user account.');
                return back();
            }
            $status = Password::sendResetLink(
                $request->only('email')
            );

            // return $status == Password::RESET_LINK_SENT
            //     ? back()->with('status', __($status))
            //     : back()->withInput($request->only('email'))
            //         ->withErrors(['email' => __($status)]);
            flash()->success(__('A fresh verification link has been sent to your email address.'));
            return back();
        } catch (Exception $e) {
            Log::error('PasswordResetLinkController::Store'.$e->getMessage());
            flash()->error('Something went wrong');
            return back();
        }
    }
}
