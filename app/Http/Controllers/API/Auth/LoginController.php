<?php

namespace App\Http\Controllers\API\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Logs in the user.
     *
     * @bodyParam email string required The email of the user.
     * @bodyParam password string required The password of the user.
     * @bodyParam role string required The role of the user. Can be either customer or contractor.
     */
    public function Login(Request $request)
    {

        // return $next($request);
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            // 'role' => 'required|in:customer,contractor',
        ]);
        try {
            if (filter_var($request->email, FILTER_VALIDATE_EMAIL) !== false) {
                $user = User::where('email', $request->email)->first();
                if (empty($user)) {
                    return Helper::jsonErrorResponse('No account found with this email.', 422, ['email' => 'No account found with this email.']);
                }
                if (empty($user->email_verified_at)) {
                    return Helper::jsonErrorResponse('Your email address has not been verified yet. Please check your inbox for the verification email and verify your account.', 403);
                }
            }

            //! Check the password
            if (!Hash::check($request->password, $user->password)) {
                return Helper::jsonErrorResponse('Invalid password', 401);
            }
            //* Generate token if email is verified
            $token = auth('api')->login($user);
            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully.',
                'code' => 200,
                'token_type' => 'bearer',
                'token' => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data' => auth('api')->user()
            ], 200);
        } catch (Exception $e) {
            Log::error('LoginController::Login' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Refreshes the JWT access token for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     * 
     * @throws \Exception If an error occurs during token refresh.
     */
    public function refreshToken()
    {
        try {
            $refreshToken = auth('api')->refresh();

            return response()->json([
                'status' => true,
                'message' => 'Access token refreshed successfully.',
                'code' => 200,
                'token_type' => 'bearer',
                'token' => $refreshToken,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data' => auth('api')->user()
            ]);
        } catch (Exception $e) {
            Log::error('LoginController::refreshToken' . $e->getMessage());
            return Helper::jsonErrorResponse($e->getMessage(), 500);
        }
    }
}
