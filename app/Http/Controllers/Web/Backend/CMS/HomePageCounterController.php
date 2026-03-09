<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use App\Enums\Page;
use App\Enums\Section;
use App\Models\CMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class HomePageCounterController extends Controller
{
    public function index(Request $request)
    {
        $containerData = CMS::where('page', Page::HomePage)->where('section', Section::CounterSection)->first();
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::CounterSectionContent)->orderBy('id')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $s = '<div class="form-check form-switch"><input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"' . ($row->status == 'active' ? ' checked' : '') . '></div>';
                    return $s;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a type="button" href="' . route('cms.home_page.counter_section.edit', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.cms.home_page.counter_section.index', compact('containerData'));
    }

    public function create()
    {
        return view('backend.layouts.cms.home_page.counter_section.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
        ]);
        try {
            $validatedData['page'] = Page::HomePage->value;
            $validatedData['section'] = Section::CounterSectionContent->value;
            $validatedData['status'] = 'active';
            CMS::create($validatedData);
            return redirect()->route('cms.home_page.counter_section.index')->with('success', 'Counter item created successfully');
        } catch (Exception $e) {
            Log::error("HomePageCounterController::store " . $e->getMessage());
            return redirect()->route('cms.home_page.counter_section.index')->with('error', 'Counter item create failed')->withInput();
        }
    }

    public function edit(string $id)
    {
        $data = CMS::where('page', Page::HomePage->value)->where('section', Section::CounterSectionContent->value)->findOrFail($id);
        return view('backend.layouts.cms.home_page.counter_section.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'sub_title' => 'nullable|string|max:255',
        ]);
        try {
            $data = CMS::findOrFail($id);
            $data->update($validatedData);
            flash()->success('Counter item updated successfully');
            return redirect()->route('cms.home_page.counter_section.index');
        } catch (Exception $e) {
            Log::error("HomePageCounterController::update " . $e->getMessage());
            flash()->error('Counter item update failed');
            return redirect()->route('cms.home_page.counter_section.index');
        }
    }

    public function destroy(string $id)
    {
        $data = CMS::findOrFail($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Counter item deleted successfully.']);
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
                ['page' => Page::HomePage->value, 'section' => Section::CounterSection->value],
                $validatedData
            );
            return redirect()->route('cms.home_page.counter_section.index')->with('success', 'Container updated successfully');
        } catch (Exception $e) {
            Log::error("HomePageCounterController::updateContainer " . $e->getMessage());
            return redirect()->route('cms.home_page.counter_section.index')->with('error', 'Container update failed');
        }
    }
}
