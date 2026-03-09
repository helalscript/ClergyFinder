<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class OnboardingScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       if ($request->ajax()) {
            $data = CMS::where('page', Page::OnboardingPage)->where('section', Section::OnboardingScreenSectionContent)->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    if (!empty($data->image) && file_exists(public_path($data->image))) {
                        return '<img src="' . asset($data->image) . '" class="wh-40 rounded-3" alt="image">';
                    }
                    return '<span class="text-muted small">No Image</span>';
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
                        <a type="button" href="' . route('cms.onboarding_screen.screens.edit', $data->id) . '"
                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                 ><i class="material-symbols-outlined fs-16 text-body">edit</i>
                            </a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
                </div>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        }
        return view("backend.layouts.cms.onboarding_screen.screens.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.cms.onboarding_screen.screens.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $validatedData['page'] = Page::OnboardingPage->value;
            $validatedData['section'] = Section::OnboardingScreenSectionContent->value;
            $validatedData['status'] = $validatedData['status'] ?? 'active';

            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload(
                    $request->file('image'),
                    'onboarding_screen',
                    time() . '_' . getFileName($request->file('image'))
                );
            }

            CMS::create($validatedData);
            return redirect()->route('cms.onboarding_screen.screens.index')->with('success', 'Onboarding screen created successfully');
        } catch (Exception $e) {
            Log::error("OnboardingScreenController::store " . $e->getMessage());
            return redirect()->route('cms.onboarding_screen.screens.index')->with('error', 'Onboarding screen create failed')->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CMS::where([
            'page' => Page::OnboardingPage->value,
            'section' => Section::OnboardingScreenSectionContent->value
        ])->findOrFail($id);

        return view("backend.layouts.cms.onboarding_screen.screens.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'description' => 'required|string',
            'sub_title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        try {
            $data = CMS::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload(
                    $request->file('image'),
                    'onboarding_screen',
                    time() . '_' . getFileName($request->file('image'))
                );
            }

            $data->update($validatedData);
            flash()->success('Onboarding screen updated successfully');
            return redirect()->route('cms.onboarding_screen.screens.index');
        } catch (Exception $e) {
            Log::error("OnboardingScreenController::update " . $e->getMessage());
            flash()->error('Onboarding screen update failed');
            return redirect()->route('cms.onboarding_screen.screens.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = CMS::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        if (!empty($data->image)) {
            Helper::fileDelete(public_path($data->image));
        }

        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Onboarding screen deleted successfully."
        ]);
    }

    public function status(Request $request, $id)
    {
        $data = CMS::find($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }
        if ($data->status == 'active') {
            $data->status = 'inactive';
        } else {
            $data->status = 'active';
        }

        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Onboarding screen status changed successfully.'
        ]);
    }
}
