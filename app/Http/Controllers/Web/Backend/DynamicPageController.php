<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DynamicPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $data = DynamicPage::latest();
        // dd($data);
        if ($request->ajax()) {
            $data = DynamicPage::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('page_content', function ($data) {
                    // Strip HTML tags and truncate the content
                    $content = strip_tags($data->page_content);
                    return $content;
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
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View" onclick="window.location.href=\'' . route('pages', $data->page_slug) . '\'">
                        <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </button>
                         <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="window.location.href=\'' . route('dynamic_page.edit', $data->id) . '\'">
                         <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </button>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.settings.dynamic_page.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.settings.dynamic_page.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ]);
        $validatedData['page_slug'] = generateUniqueSlug($request->page_title);
        $validatedData['status'] = 'active';
        DynamicPage::create($validatedData);
        flash()->success('Dynamic Page Created Successfully');
        return redirect()->route('dynamic_page.index');
    }

    /**
     * Display the specified resource.
     */
    public function showDaynamicPage($page_slug)
    {
        $page = DynamicPage::where('page_slug', $page_slug)->where('status', 'active')->firstOrFail();
        $pages = DynamicPage::where('status', 'active')->get();

        return view('backend.layouts.settings.dynamic_page.show', compact(['page', 'pages']));
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = DynamicPage::findOrFail($id);
        return view("backend.layouts.settings.dynamic_page.edit", compact("data"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'page_title' => 'required|string|max:100',
            'page_content' => 'required|string',
        ]);
        $data = DynamicPage::findOrFail($id);
        $validatedData['page_slug'] = generateUniqueSlug($request->page_title, $id);
        $data->update($validatedData);
        flash()->success('Dynamic Page Updated Successfully');
        return redirect()->route('dynamic_page.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = DynamicPage::findOrFail($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        // if (!empty($data->image)) {
        //     Helper::fileDelete(public_path($data->image));
        // }
        // if (!empty($data->background_image)) {
        //     Helper::fileDelete(public_path($data->background_image));
        // }

        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }

    public function status(Request $request, $id)
    {

        $data = DynamicPage::find($id);
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
}
