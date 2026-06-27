<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clergy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LiveSessionTestController extends Controller
{
    public function index()
    {
        return view('dev.live-session-test');
    }

    public function getTestUsers()
    {
        $seekers = User::where('role', 'seeker')->orderBy('id', 'desc')->get(['id', 'name', 'email']);
        $clergy = User::where('role', 'clergy')->with('clergy')->orderBy('id', 'desc')->get(['id', 'name', 'email']);

        return response()->json([
            'status' => true,
            'seekers' => $seekers,
            'clergy' => $clergy,
        ]);
    }

    public function getQuickToken(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // If clergy, let's auto-prepare their profile to avoid permissions/availability issues
        if ($user->role === 'clergy') {
            $clergy = $user->clergy;
            if (!$clergy) {
                $clergy = Clergy::create([
                    'user_id' => $user->id,
                    'religion' => 'Christianity',
                    'preferred_language' => ['English'],
                    'address' => 'Test Address',
                    'city' => 'Test City',
                    'state' => 'TS',
                    'zip_code' => '12345',
                    'country' => 'USA',
                    'bio' => 'Auto prepared biography',
                ]);
            }
            $clergy->update([
                'registration_status' => 'approved',
                'is_live_session' => true,
                'is_available_for_live' => true,
                'status' => 'active',
            ]);
        }

        $token = auth('api')->login($user);

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function createTestUser(Request $request)
    {
        $request->validate([
            'role' => 'required|in:seeker,clergy',
        ]);

        $role = $request->role;
        $rand = rand(100, 999);
        
        $user = User::create([
            'name' => 'Test ' . ucfirst($role) . ' ' . $rand,
            'email' => 'test' . $role . $rand . '@example.com',
            'password' => Hash::make('12345678'),
            'role' => $role,
            'email_verified_at' => now(),
        ]);

        if ($role === 'clergy') {
            Clergy::create([
                'user_id' => $user->id,
                'religion' => 'Christianity',
                'preferred_language' => ['English'],
                'address' => 'Test Address',
                'city' => 'Test City',
                'state' => 'TS',
                'zip_code' => '12345',
                'country' => 'USA',
                'bio' => 'Auto created clergy biography',
                'registration_status' => 'approved',
                'is_live_session' => true,
                'is_available_for_live' => true,
                'status' => 'active',
            ]);
        }

        $token = auth('api')->login($user);

        return response()->json([
            'status' => true,
            'message' => 'Test ' . $role . ' created successfully.',
            'token' => $token,
            'user' => $user,
        ]);
    }
}
