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

class HomePageHowItWorksController extends Controller
{
    public function index(Request $request)
    {
        $containerData = CMS::where('page', Page::HomePage)->where('section', Section::HomeHowItWorksSection)->first();
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::HomeHowItWorksSectionContent)->orderBy('id')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if (!empty($row->image) && file_exists(public_path($row->image))) {
                        return '<img src="' . asset($row->image) . '" class="wh-40 rounded-3" alt="image">';
                    }
                    return '<span class="text-muted small">No Image</span>';
                })
                ->addColumn('status', function ($row) {
                    $s = '<div class="form-check form-switch"><input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"' . ($row->status == 'active' ? ' checked' : '') . '></div>';
                    return $s;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a type="button" href="' . route('cms.home_page.how_it_works_section.edit', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        }
        return view('backend.layouts.cms.home_page.how_it_works_section.index', compact('containerData'));
    }

    public function create()
    {
        return view('backend.layouts.cms.home_page.how_it_works_section.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);
        try {
            $validatedData['page'] = Page::HomePage->value;
            $validatedData['section'] = Section::HomeHowItWorksSectionContent->value;
            $validatedData['status'] = 'active';
            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'how_it_works_section', time() . '_' . getFileName($request->file('image')));
            }
            CMS::create($validatedData);
            return redirect()->route('cms.home_page.how_it_works_section.index')->with('success', 'Item created successfully');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorksController::store " . $e->getMessage());
            return redirect()->route('cms.home_page.how_it_works_section.index')->with('error', 'Item create failed')->withInput();
        }
    }

    public function edit(string $id)
    {
        $data = CMS::where('page', Page::HomePage->value)->where('section', Section::HomeHowItWorksSectionContent->value)->findOrFail($id);
        return view('backend.layouts.cms.home_page.how_it_works_section.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);
        try {
            $data = CMS::findOrFail($id);
            if ($request->hasFile('image')) {
                if ($data->image && file_exists(public_path($data->image))) Helper::fileDelete(public_path($data->image));
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'how_it_works_section', time() . '_' . getFileName($request->file('image')));
            }
            $data->update($validatedData);
            flash()->success('Item updated successfully');
            return redirect()->route('cms.home_page.how_it_works_section.index');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorksController::update " . $e->getMessage());
            flash()->error('Item update failed');
            return redirect()->route('cms.home_page.how_it_works_section.index');
        }
    }

    public function destroy(string $id)
    {
        $data = CMS::findOrFail($id);
        if ($data->image && file_exists(public_path($data->image))) Helper::fileDelete(public_path($data->image));
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Item deleted successfully.']);
    }

    public function status(Request $request, $id)
    {
        $data = CMS::findOrFail($id);
        $data->status = $data->status == 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json(['success' => true, 'message' => 'Status changed successfully.']);
    }

    public function updateContainer(Request $request)
    {
        $validatedData = $request->validate(['title' => 'nullable|string|max:255']);
        try {
            CMS::updateOrCreate(
                ['page' => Page::HomePage->value, 'section' => Section::HomeHowItWorksSection->value],
                $validatedData
            );
            return redirect()->route('cms.home_page.how_it_works_section.index')->with('success', 'Container updated successfully');
        } catch (Exception $e) {
            Log::error("HomePageHowItWorksController::updateContainer " . $e->getMessage());
            return redirect()->route('cms.home_page.how_it_works_section.index')->with('error', 'Container update failed');
        }
    }
}
