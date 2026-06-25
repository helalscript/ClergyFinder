<?php

namespace App\Http\Controllers\API\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\LiveSessionRequest;
use App\Services\AgoraService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class LiveSessionAgoraController extends Controller
{
    public function join(int $id, AgoraService $agoraService): JsonResponse
    {
        try {
            $user = auth('api')->user();
            $req = LiveSessionRequest::find($id);

            if (! $req) {
                return Helper::jsonResponse(false, 'Live session record not found', 404);
            }

            if (! $agoraService->userCanAccessSession($req, $user)) {
                return Helper::jsonErrorResponse('Unauthorized', 403);
            }

            if (! $agoraService->canJoinSession($req)) {
                return Helper::jsonErrorResponse('Live session is not ready for a video call.', 409);
            }

            $agora = $agoraService->buildJoinCredentials($req, $user);

            return Helper::jsonResponse(true, 'Agora join credentials generated', 200, [
                'agora' => $agora,
            ]);
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'agora_not_configured') {
                return Helper::jsonErrorResponse('Agora is not configured on the server.', 503);
            }

            return Helper::jsonErrorResponse($e->getMessage(), 409);
        } catch (Exception $e) {
            Log::error('LiveSessionAgoraController::join '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to generate Agora join credentials', 500);
        }
    }
}
