<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\SubCategory;
use DB;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the units with DataTables support.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('users.role', 'user')
                ->leftJoin('client_profiles', 'users.id', '=', 'client_profiles.user_id')
                ->select('users.*', 'client_profiles.company_title')
                ->latest();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('avatar', function ($data) {
                    return '<img src="' . asset($data->avatar ?? 'backend/admin/assets/images/avatar_defult.png') . '" class="wh-40 rounded-3" alt="no image found">';
                })
                ->addColumn('company_title', function ($data) {
                    return $data->company_title ?? '';
                })
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $data->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px"' . $data->id . '" name="status"';

                    if ($data->status == "active") {
                        $status .= ' checked';
                    }

                    $status .= '>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick="window.location.href=\'' . route('users.show', $data->id) . '\'">
                        <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </button>
                         <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="window.location.href=\'' . route('users.edit', $data->id) . '\'">
                         <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </button>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.user.index");
    }
    /**
     * Show the form for creating a new data.
     */
    public function create()
    {
        return view("backend.layouts.user.create");
    }
    /**
     * Store a newly created data in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5240',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'company_title' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            // store avatar
            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'Candidate', time() . '_' . getFileName($request->file('avatar')));
            }
            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['role'] = 'user';
            $validatedData['email_verified_at'] = now();
            //create user
            $user = User::create($validatedData);
            $user->clientProfile()->create(['company_title' => $validatedData['company_title']]);
            DB::commit();
            return redirect()->route('users.index')->with('success', 'Candidate created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("UserController::store" . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Candidate created failed.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::with('clientProfile')->findOrFail($id);
        return view("backend.layouts.user.edit", compact("data"));
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = User::with('clientProfile')->findOrFail($id);
        // dd($data->toArray());
        return view("backend.layouts.user.show", compact("data"));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5240',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:6',
            'company_title' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();
            // store avatar
            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'Candidate', time() . '_' . getFileName($request->file('avatar')));
            } else {
                unset($validatedData['avatar']);
            }

            if ($request->filled('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $user = User::findOrFail($id);
            $user->update($validatedData);
            $user->clientProfile()->update(['company_title' => $validatedData['company_title']]);
            DB::commit();
            return redirect()->route('users.index')->with('success', 'Candidate updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("UserController::update" . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Candidate updated failed.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = User::findOrFail($id);
        // check if exists
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }
        // delete 
        if (!empty($data->image)) {
            Helper::fileDelete(public_path($data->image));
        }
        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }

    /**
     * Change the status of the specified resource from storage.
     */
    public function status(Request $request, $id)
    {
        $data = User::find($id);

        // check if exists
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        // toggle status 
        if ($data->status == 'active') {
            $data->status = 'inactive';
        } else {
            $data->status = 'active';
        }

        // save the changes
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.'
        ]);
    }
}
