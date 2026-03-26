<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FaqCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = FaqCategory::latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px"' . $row->id . '" name="status"';
                    if ($row->status === 'active') {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '</div>';

                    return $status;
                })
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a href="' . route('faq_categories.edit', $row->id) . '"
                            class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                            <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete"
                            onclick="deleteRecord(event,' . $row->id . ')">
                            <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.faq_categories.index');
    }

    public function create()
    {
        return view('backend.layouts.faq_categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            FaqCategory::create($validated);
            flash()->success('Faq category created successfully');

            return redirect()->route('faq_categories.index');
        } catch (\Exception $e) {
            Log::error('FaqCategoryController::store ' . $e->getMessage());
            flash()->error('Faq category creation failed');

            return redirect()->route('faq_categories.index');
        }
    }

    public function edit(string $id)
    {
        $data = FaqCategory::findOrFail($id);

        return view('backend.layouts.faq_categories.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = FaqCategory::findOrFail($id);
            $data->update($validated);

            flash()->success('Faq category updated successfully');

            return redirect()->route('faq_categories.index');
        } catch (\Exception $e) {
            Log::error('FaqCategoryController::update ' . $e->getMessage());
            flash()->error('Faq category update failed');

            return redirect()->route('faq_categories.index');
        }
    }

    public function destroy(string $id)
    {
        $data = FaqCategory::findOrFail($id);
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully.',
        ]);
    }

    public function status(Request $request, $id)
    {
        $data = FaqCategory::find($id);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.',
        ]);
    }
}

