<?php

namespace App\Http\Controllers\API\V1\User;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Clergy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClergyController extends Controller
{
    public function index(Request $request)
    {
        try {

            $fillterByReligion = $request->has('religion') ? $request->religion : null;
            $fillterByLanguage = $request->has('language') ? $request->language : null;
            $fillterByCity = $request->has('city') ? $request->city : null;
            $search = $request->has('search') ? $request->search : null;

            $per_page = $request->has('per_page') ? $request->per_page : 25;
            $clergy = Clergy::query()
                ->publicDirectory()
                ->with('user:id,name,avatar')
                ->select('id', 'user_id', 'religion', 'denomination', 'bio', 'address', 'city', 'preferred_language', 'available_at', 'specialities', 'is_verified', 'is_live_session', 'is_prayer_request', 'is_available_for_live', 'status', 'registration_status')
                ->when($search, function ($query) use ($search) {
                    $query->where('religion', 'like', '%'.$search.'%')
                        ->orWhere('bio', 'like', '%'.$search.'%')
                        ->orWhere('address', 'like', '%'.$search.'%')
                        ->orWhere('city', 'like', '%'.$search.'%')
                        ->orWhere('preferred_language', 'like', '%'.$search.'%')
                        ->orWhere('available_at', 'like', '%'.$search.'%')
                        ->orWhere('specialities', 'like', '%'.$search.'%')
                        ->orWhere('is_live_session', 'like', '%'.$search.'%')
                        ->orWhere('is_prayer_request', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', '%'.$search.'%');
                        });
                })
                ->when($fillterByReligion, function ($query) use ($fillterByReligion) {
                    $query->where('religion', 'like', '%'.$fillterByReligion.'%');
                })
                ->when($fillterByLanguage, function ($query) use ($fillterByLanguage) {
                    $query->where('preferred_language', 'like', '%'.$fillterByLanguage.'%');
                })
                ->when($fillterByCity, function ($query) use ($fillterByCity) {
                    $query->where('city', 'like', '%'.$fillterByCity.'%');
                })->paginate($per_page);

            return Helper::jsonResponse(true, 'Clergy fetched successfully', 200, $clergy, true);
        } catch (Exception $e) {
            Log::error('ClergyController::getClergy '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch clergy '.$e->getMessage(), 500);
        }
    }

    public function show(string $id)
    {
        try {
            $clergy = Clergy::query()
                ->publicDirectory()
                ->with('user:id,name,avatar')
                ->select('id', 'user_id', 'religion', 'denomination', 'bio', 'address', 'city', 'preferred_language', 'available_at', 'specialities', 'is_verified', 'is_live_session', 'is_prayer_request', 'is_available_for_live', 'status', 'registration_status')
                ->find($id);
            if (! $clergy) {
                return Helper::jsonResponse(false, 'Clergy not found', 404);
            }

            return Helper::jsonResponse(true, 'Clergy fetched successfully', 200, $clergy);
        } catch (Exception $e) {
            Log::error('ClergyController::show '.$e->getMessage());

            return Helper::jsonErrorResponse('Failed to fetch clergy '.$e->getMessage(), 500);
        }
    }
}
