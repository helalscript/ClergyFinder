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
use Yajra\DataTables\DataTables;

class HomePageWorkProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $containerData = CMS::where('page', Page::HomePage)->where('section', Section::WorkProcessSection)->first();
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::WorkProcessSectionContent)->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return '<img src="' . asset($data->image ?? '') . '" class="wh-40 rounded-3" alt="no image found">';
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
                        <a type="button" href="' . route('cms.home_page.work_process_section.edit', $data->id) . '"
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
        return view("backend.layouts.cms.home_page.work_process_section.index", compact('containerData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.cms.home_page.work_process_section.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5240',
        ]);

        try {
            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'work_process_section', time() . '_' . getFileName($request->file('image')));
            }
            $validatedData['page'] = Page::HomePage->value;
            $validatedData['section'] = Section::WorkProcessSectionContent->value;
            CMS::Create($validatedData);
            return redirect()->route('cms.home_page.work_process_section.index')->with('success', 'Item created Successfully');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorkController::store" . $e->getMessage());
            return redirect()->route('cms.home_page.work_process_section.index')->with('error', 'Item created Failed')->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CMS::where([
            'page' => Page::HomePage->value,
            'section' => Section::WorkProcessSectionContent->value
        ])->findOrFail($id);

        return view("backend.layouts.cms.home_page.work_process_section.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $data = CMS::findOrFail($id);

            if ($request->hasFile('image')) {
                if (!empty($data->image)) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'work_process_section', time() . '_' . getFileName($request->file('image')));
            }

            $data->update($validatedData);
            flash()->success('Item updated Successfully');
            return redirect()->route('cms.home_page.work_process_section.index');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorkController::store" . $e->getMessage());
            flash()->error('Item updated Failed');
            return redirect()->route('cms.home_page.work_process_section.index');
        }
    }

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
            "message" => "Item deleted successfully."
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
            'message' => 'Item status changed successfully.'
        ]);
    }

    // update main Faq container
    public function updateContainer(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            // 'description' => 'required|string|max:255',
        ]);

        try {
            $data = CMS::updateOrCreate(
                [
                    'page' => Page::HomePage->value,
                    'section' => Section::WorkProcessSection->value,
                ],
                $validatedData
            );

            return redirect()->route('cms.home_page.work_process_section.index')->with('success', 'Container update successfully');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorkController::updateHowItWorkContainer" . $e->getMessage());
            return redirect()->route('cms.home_page.work_process_section.index')->with('error', 'Container update unsuccessfully');
        }
    }
}
