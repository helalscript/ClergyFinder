<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Clergy;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class UserManagementController extends Controller
{
    // ===================== Clergy =====================
    public function clergyIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'clergy')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    $src = $row->avatar ? asset($row->avatar) : asset('backend/admin/assets/images/avatar_defult.png');
                    return '<img src="' . $src . '" class="wh-40 rounded-3" alt="avatar">';
                })
                ->addColumn('status', function ($row) {
                    $s = '<div class="form-check form-switch">';
                    $s .= '<input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"' . ($row->status == 'active' ? ' checked' : '') . '>';
                    $s .= '</div>';
                    return $s;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a type="button" href="' . route('user_management.clergy.show', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-primary">visibility</i></a>
                        <a type="button" href="' . route('user_management.clergy.edit', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.user_management.clergy.index');
    }

    public function clergyCreate()
    {
        return view('backend.layouts.user_management.clergy.create');
    }

    public function clergyStore(Request $request)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:50|unique:users,phone',
            'gender' => 'nullable|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'password' => 'required|string|min:6',
        ]);

        $validatedClergy = $request->validate([
            'religion' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bio' => 'nullable|string',
            'available_at' => 'nullable|string|max:255',
            'specialities' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
            'is_live_session' => 'nullable|boolean',
            'is_prayer_request' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $validatedUser['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', time() . '_' . getFileName($request->file('avatar')));
            }

            $validatedUser['password'] = bcrypt($validatedUser['password']);
            $validatedUser['email_verified_at'] = now();
            $validatedUser['role'] = 'clergy';
            $validatedUser['status'] = 'active';

            $user = User::create($validatedUser);

            $clergyPayload = [
                'user_id' => $user->id,
                'religion' => $validatedClergy['religion'] ?? null,
                'preferred_language' => !empty($validatedClergy['preferred_language']) ? array_values(array_filter(array_map('trim', explode(',', $validatedClergy['preferred_language'])))) : null,
                'address' => $validatedClergy['address'] ?? null,
                'city' => $validatedClergy['city'] ?? null,
                'state' => $validatedClergy['state'] ?? null,
                'zip_code' => $validatedClergy['zip_code'] ?? null,
                'country' => $validatedClergy['country'] ?? null,
                'latitude' => $validatedClergy['latitude'] ?? null,
                'longitude' => $validatedClergy['longitude'] ?? null,
                'bio' => $validatedClergy['bio'] ?? null,
                'available_at' => $validatedClergy['available_at'] ?? null,
                'specialities' => !empty($validatedClergy['specialities']) ? array_values(array_filter(array_map('trim', explode(',', $validatedClergy['specialities'])))) : null,
                'is_verified' => (bool) ($request->input('is_verified', false)),
                'is_live_session' => (bool) ($request->input('is_live_session', true)),
                'is_prayer_request' => (bool) ($request->input('is_prayer_request', true)),
                'status' => 'active',
            ];

            Clergy::create($clergyPayload);

            DB::commit();
            return redirect()->route('user_management.clergy.index')->with('success', 'Clergy user created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("UserManagementController::clergyStore " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Clergy user create failed.');
        }
    }

    public function clergyShow(string $id)
    {
        $data = User::with('clergy')->where('role', 'clergy')->findOrFail($id);
        return view('backend.layouts.user_management.clergy.show', compact('data'));
    }

    public function clergyEdit(string $id)
    {
        $data = User::with('clergy')->where('role', 'clergy')->findOrFail($id);
        return view('backend.layouts.user_management.clergy.edit', compact('data'));
    }

    public function clergyUpdate(Request $request, string $id)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:50|unique:users,phone,' . $id,
            'gender' => 'nullable|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        $validatedClergy = $request->validate([
            'religion' => 'nullable|string|max:255',
            'preferred_language' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'bio' => 'nullable|string',
            'available_at' => 'nullable|string|max:255',
            'specialities' => 'nullable|string',
            'is_verified' => 'nullable|boolean',
            'is_live_session' => 'nullable|boolean',
            'is_prayer_request' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $user = User::where('role', 'clergy')->findOrFail($id);

            if ($request->hasFile('avatar')) {
                if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
                    Helper::fileDelete(public_path($user->avatar));
                }
                $validatedUser['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', time() . '_' . getFileName($request->file('avatar')));
            } else {
                unset($validatedUser['avatar']);
            }

            if ($request->filled('password')) {
                $validatedUser['password'] = bcrypt($validatedUser['password']);
            } else {
                unset($validatedUser['password']);
            }

            $user->update($validatedUser);

            $clergyPayload = [
                'religion' => $validatedClergy['religion'] ?? null,
                'preferred_language' => !empty($validatedClergy['preferred_language']) ? array_values(array_filter(array_map('trim', explode(',', $validatedClergy['preferred_language'])))) : null,
                'address' => $validatedClergy['address'] ?? null,
                'city' => $validatedClergy['city'] ?? null,
                'state' => $validatedClergy['state'] ?? null,
                'zip_code' => $validatedClergy['zip_code'] ?? null,
                'country' => $validatedClergy['country'] ?? null,
                'latitude' => $validatedClergy['latitude'] ?? null,
                'longitude' => $validatedClergy['longitude'] ?? null,
                'bio' => $validatedClergy['bio'] ?? null,
                'available_at' => $validatedClergy['available_at'] ?? null,
                'specialities' => !empty($validatedClergy['specialities']) ? array_values(array_filter(array_map('trim', explode(',', $validatedClergy['specialities'])))) : null,
                'is_verified' => (bool) ($request->input('is_verified', false)),
                'is_live_session' => (bool) ($request->input('is_live_session', true)),
                'is_prayer_request' => (bool) ($request->input('is_prayer_request', true)),
            ];

            Clergy::updateOrCreate(['user_id' => $user->id], $clergyPayload);

            DB::commit();
            return redirect()->route('user_management.clergy.index')->with('success', 'Clergy user updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("UserManagementController::clergyUpdate " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Clergy user update failed.');
        }
    }

    public function clergyDestroy(string $id)
    {
        $user = User::where('role', 'clergy')->findOrFail($id);
        if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
            Helper::fileDelete(public_path($user->avatar));
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function clergyStatus(Request $request, $id)
    {
        $user = User::where('role', 'clergy')->findOrFail($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
        return response()->json(['success' => true, 'message' => 'Status changed successfully.']);
    }

    // ===================== Seeker =====================
    public function seekerIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('role', 'seeker')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    $src = $row->avatar ? asset($row->avatar) : asset('backend/admin/assets/images/avatar_defult.png');
                    return '<img src="' . $src . '" class="wh-40 rounded-3" alt="avatar">';
                })
                ->addColumn('status', function ($row) {
                    $s = '<div class="form-check form-switch">';
                    $s .= '<input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"' . ($row->status == 'active' ? ' checked' : '') . '>';
                    $s .= '</div>';
                    return $s;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a type="button" href="' . route('user_management.seeker.show', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-primary">visibility</i></a>
                        <a type="button" href="' . route('user_management.seeker.edit', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.user_management.seeker.index');
    }

    public function seekerCreate()
    {
        return view('backend.layouts.user_management.seeker.create');
    }

    public function seekerStore(Request $request)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'phone' => 'nullable|string|max:50|unique:users,phone',
            'gender' => 'nullable|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'password' => 'required|string|min:6',
        ]);

        try {
            if ($request->hasFile('avatar')) {
                $validatedUser['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', time() . '_' . getFileName($request->file('avatar')));
            }
            $validatedUser['password'] = bcrypt($validatedUser['password']);
            $validatedUser['role'] = 'seeker';
            $validatedUser['email_verified_at'] = now();
            $validatedUser['status'] = 'active';

            User::create($validatedUser);
            return redirect()->route('user_management.seeker.index')->with('success', 'Seeker user created successfully.');
        } catch (Exception $e) {
            Log::error("UserManagementController::seekerStore " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Seeker user create failed.');
        }
    }

    public function seekerShow(string $id)
    {
        $data = User::where('role', 'seeker')->findOrFail($id);
        return view('backend.layouts.user_management.seeker.show', compact('data'));
    }

    public function seekerEdit(string $id)
    {
        $data = User::where('role', 'seeker')->findOrFail($id);
        return view('backend.layouts.user_management.seeker.edit', compact('data'));
    }

    public function seekerUpdate(Request $request, string $id)
    {
        $validatedUser = $request->validate([
            'name' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:50|unique:users,phone,' . $id,
            'gender' => 'nullable|in:male,female,others',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'password' => 'sometimes|nullable|string|min:6',
        ]);

        try {
            $user = User::where('role', 'seeker')->findOrFail($id);

            if ($request->hasFile('avatar')) {
                if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
                    Helper::fileDelete(public_path($user->avatar));
                }
                $validatedUser['avatar'] = Helper::fileUpload($request->file('avatar'), 'user/avatar', time() . '_' . getFileName($request->file('avatar')));
            } else {
                unset($validatedUser['avatar']);
            }

            if ($request->filled('password')) {
                $validatedUser['password'] = bcrypt($validatedUser['password']);
            } else {
                unset($validatedUser['password']);
            }

            $user->update($validatedUser);
            return redirect()->route('user_management.seeker.index')->with('success', 'Seeker user updated successfully.');
        } catch (Exception $e) {
            Log::error("UserManagementController::seekerUpdate " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Seeker user update failed.');
        }
    }

    public function seekerDestroy(string $id)
    {
        $user = User::where('role', 'seeker')->findOrFail($id);
        if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
            Helper::fileDelete(public_path($user->avatar));
        }
        $user->delete();
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }

    public function seekerStatus(Request $request, $id)
    {
        $user = User::where('role', 'seeker')->findOrFail($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();
        return response()->json(['success' => true, 'message' => 'Status changed successfully.']);
    }
}
