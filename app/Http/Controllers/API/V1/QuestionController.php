<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\UserType;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\UserAnswer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Get questions by type (clergy|seeker).
     */
    public function getQuestions(string $type): JsonResponse
    {
        try {
            $userType = $type === 'clergy' ? UserType::Clergy : UserType::Seeker;
            $questions = Question::with('options')
                ->where('user_type', $userType)
                ->active()
                ->orderBy('sort_order')
                ->select('id', 'question', 'image', 'answer_type', 'sort_order')
                ->get()
                ->map(function ($q) {
                    $data = $q->only('id', 'question', 'image', 'answer_type', 'sort_order');
                    if (in_array($q->answer_type?->value ?? '', ['single_choice', 'multiple_choice'])) {
                        $data['options'] = $q->options->map(fn($o) => ['id' => $o->id, 'option_text' => $o->option_text, 'sort_order' => $o->sort_order]);
                    } else {
                        $data['options'] = [];
                    }
                    return $data;
                });

            return Helper::jsonResponse(true, 'Questions fetched successfully.', 200, $questions);
        } catch (Exception $e) {
            Log::error("QuestionController::getQuestions " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to retrieve questions.', 500);
        }
    }

    /**
     * Save user answer (userAnswer).
     */
    public function saveUserAnswer(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'question_id' => 'required|exists:questions,id',
                'user_answer' => 'nullable|string',
                'selected_option_ids' => 'nullable|array',
                'selected_option_ids.*' => 'integer|exists:question_options,id',
            ]);

            $validated['user_id'] = auth('api')->id();

            $saveData = [
                'user_answer' => $validated['user_answer'] ?? null,
                'selected_option_ids' => $validated['selected_option_ids'] ?? null,
            ];

            $answer = UserAnswer::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'question_id' => $validated['question_id'],
                ],
                $saveData
            );

            return Helper::jsonResponse(true, 'Answer saved successfully.', 200, $answer);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'errors' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error("QuestionController::saveUserAnswer " . $e->getMessage());
            return Helper::jsonErrorResponse('Failed to save answer.', 500);
        }
    }
}
