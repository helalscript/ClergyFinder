<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Enums\AnswerType;
use App\Enums\UserType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionOption;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class QuestionPageController extends Controller
{
    protected function getUserType(): UserType
    {
        return str_contains(request()->route()->getName(), 'clergy') ? UserType::Clergy : UserType::Seeker;
    }

    protected function getRoutePrefix(): string
    {
        return $this->getUserType() === UserType::Clergy ? 'clergy' : 'seeker';
    }

    public function index(Request $request)
    {
        $userType = $this->getUserType();
        if ($request->ajax()) {
            $data = Question::where('user_type', $userType)->orderBy('sort_order')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if (!empty($row->image) && file_exists(public_path($row->image))) {
                        return '<img src="' . asset($row->image) . '" class="wh-40 rounded-3" alt="image">';
                    }
                    return '<span class="text-muted small">No Image</span>';
                })
                ->addColumn('answer_type', function ($row) {
                    return $row->answer_type?->value ?? '-';
                })
                ->addColumn('status', function ($row) {
                    $routeName = 'cms.question_page.' . $this->getRoutePrefix() . '.status';
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $row->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"';
                    if ($row->status == 'active') {
                        $status .= ' checked';
                    }
                    $status .= '></div>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $editRoute = 'cms.question_page.' . $this->getRoutePrefix() . '.edit';
                    return '<div class="action-wrapper">
                        <a type="button" href="' . route($editRoute, $row->id) . '" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2">
                            <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $row->id . ')">
                            <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
                    </div>';
                })
                ->rawColumns(['status', 'action', 'image'])
                ->make(true);
        }
        return view('backend.layouts.cms.question_page.index', compact('userType'));
    }

    public function create()
    {
        $userType = $this->getUserType();
        $answerTypes = AnswerType::cases();
        return view('backend.layouts.cms.question_page.create', compact('userType', 'answerTypes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'question' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'answer_type' => 'nullable|string|in:text,textarea,single_choice,multiple_choice,yes_no',
            'answer' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        try {
            $validatedData['user_type'] = $this->getUserType();
            $validatedData['status'] = 'active';

            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload(
                    $request->file('image'),
                    'question_page',
                    time() . '_' . getFileName($request->file('image'))
                );
            }

            $question = Question::create($validatedData);

            if (in_array($validatedData['answer_type'] ?? '', ['single_choice', 'multiple_choice']) && !empty($validatedData['options'])) {
                foreach ($validatedData['options'] as $i => $opt) {
                    if (!empty(trim($opt ?? ''))) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_text' => trim($opt),
                            'sort_order' => $i,
                        ]);
                    }
                }
            }

            $prefix = $this->getRoutePrefix();
            return redirect()->route("cms.question_page.{$prefix}.index")->with('success', 'Question created successfully');
        } catch (Exception $e) {
            Log::error("QuestionPageController::store " . $e->getMessage());
            $prefix = $this->getRoutePrefix();
            return redirect()->route("cms.question_page.{$prefix}.index")->with('error', 'Question create failed')->withInput();
        }
    }

    public function edit(string $id)
    {
        $userType = $this->getUserType();
        $data = Question::with('options')->where('user_type', $userType)->findOrFail($id);
        $answerTypes = AnswerType::cases();
        return view('backend.layouts.cms.question_page.edit', compact('data', 'userType', 'answerTypes'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'question' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'answer_type' => 'nullable|string|in:text,textarea,single_choice,multiple_choice,yes_no',
            'answer' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        try {
            $data = Question::findOrFail($id);

            if ($request->hasFile('image')) {
                if ($data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload(
                    $request->file('image'),
                    'question_page',
                    time() . '_' . getFileName($request->file('image'))
                );
            }

            $data->update($validatedData);

            $data->options()->delete();
            if (in_array($validatedData['answer_type'] ?? '', ['single_choice', 'multiple_choice']) && !empty($validatedData['options'])) {
                foreach ($validatedData['options'] as $i => $opt) {
                    if (!empty(trim($opt ?? ''))) {
                        QuestionOption::create([
                            'question_id' => $data->id,
                            'option_text' => trim($opt),
                            'sort_order' => $i,
                        ]);
                    }
                }
            }

            flash()->success('Question updated successfully');
            $prefix = $this->getRoutePrefix();
            return redirect()->route("cms.question_page.{$prefix}.index");
        } catch (Exception $e) {
            Log::error("QuestionPageController::update " . $e->getMessage());
            flash()->error('Question update failed');
            $prefix = $this->getRoutePrefix();
            return redirect()->route("cms.question_page.{$prefix}.index");
        }
    }

    public function destroy(string $id)
    {
        $data = Question::findOrFail($id);
        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }
        if (!empty($data->image)) {
            Helper::fileDelete(public_path($data->image));
        }
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Question deleted successfully.']);
    }

    public function status(Request $request, $id)
    {
        $data = Question::find($id);
        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }
        $data->status = $data->status == 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json(['success' => true, 'message' => 'Question status changed successfully.']);
    }
}
