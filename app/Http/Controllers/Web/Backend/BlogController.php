<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $containerData = CMS::where('page', Page::ResourcesPage)->where('section', Section::Blogs)->first();
        if ($request->ajax()) {
            $data = Blog::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('thumbnail', function ($data) {
                    return '<img src="' . asset($data->thumbnail ?? '') . '" class="wh-40 rounded-3" alt="no thumbnail found">';
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
                        <a type="button" href="' . route('blogs.edit', $data->id) . '"
                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                 ><i class="material-symbols-outlined fs-16 text-body">edit</i>
                            </a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['status', 'action', 'thumbnail'])
                ->make(true);
        }
        return view("backend.layouts.blog.index", compact('containerData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.blog.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5240',
            'description' => 'required|string',
            'tags' => 'nullable|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:50', // Each tag should be a string
        ]);
        try {
            if ($request->hasFile('thumbnail')) {
                $validatedData['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'blog', time() . '_' . getFileName($request->file('thumbnail')));
            }
            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it
            $validatedData['slug'] = Helper::makeSlug($validatedData['title'],'blogs');
            Blog::Create($validatedData);
            return redirect()->route('blogs.index')->with('success', 'Item created Successfully');
        } catch (Exception $e) {
            Log::error("AboutPageHowItWorkController::store" . $e->getMessage());
            return redirect()->route('blogs.index')->with('error', 'Item created Failed')->withInput();
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Blog::findOrFail($id);

        return view("backend.layouts.blog.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'nullable|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:50', // Each tag should be a string
        ]);

        try {
            $data = Blog::findOrFail($id);

            if ($request->hasFile('thumbnail')) {
                if (!empty($data->thumbnail)) {
                    Helper::fileDelete(public_path($data->thumbnail));
                }
                $validatedData['thumbnail'] = Helper::fileUpload($request->file('thumbnail'), 'how_it_works_section', time() . '_' . getFileName($request->file('thumbnail')));
            }
            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it
            $data->update($validatedData);
            flash()->success('Item updated Successfully');
            return redirect()->route('blogs.index');
        } catch (Exception $e) {
            Log::error("AboutPageHowItWorkController::store" . $e->getMessage());
            flash()->error('Item updated Failed');
            return redirect()->route('blogs.index');
        }
    }

    public function destroy(string $id)
    {
        $data = Blog::findOrFail($id);
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
        $data = Blog::find($id);
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
        ]);

        try {
            $data = CMS::updateOrCreate(
                [
                    'page' => Page::ResourcesPage->value,
                    'section' => Section::Blogs->value,
                ],
                $validatedData
            );

            return redirect()->route('blogs.index')->with('success', 'Container update successfully');
        } catch (Exception $e) {
            Log::error("AboutPageHowItWorkController::updateHowItWorkContainer" . $e->getMessage());
            return redirect()->route('blogs.index')->with('error', 'Container update unsuccessfully');
        }
    }
}
