<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PrayerRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = PrayerRequest::with(['seeker:id,name,email', 'clergy:id,name,email'])->latest();

            if ($request->filled('status') && in_array($request->status, ['pending', 'replied'])) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('seeker_name', fn($row) => $row->seeker?->name ?? '-')
                ->addColumn('clergy_name', fn($row) => $row->clergy?->name ?? '-')
                ->addColumn('message_preview', fn($row) => \Str::limit($row->message, 50))
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a href="' . route('admin.prayer_requests.show', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-primary">visibility</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.prayer_requests.index');
    }

    public function show(int $id)
    {
        $data = PrayerRequest::with(['seeker', 'clergy'])->findOrFail($id);
        $clergyList = User::where('role', 'clergy')->whereHas('clergy', fn($q) => $q->where('is_prayer_request', true))->get(['id', 'name', 'email']);
        return view('backend.layouts.prayer_requests.show', compact('data', 'clergyList'));
    }

    public function assignClergy(Request $request, int $id)
    {
        $validated = Validator::make($request->all(), [
            'clergy_id' => 'required|exists:users,id',
        ])->validate();

        $prayerRequest = PrayerRequest::findOrFail($id);
        $prayerRequest->update(['clergy_id' => $validated['clergy_id']]);

        return redirect()->route('admin.prayer_requests.show', $id)->with('success', 'Clergy assigned successfully.');
    }

    public function destroy(int $id)
    {
        $data = PrayerRequest::findOrFail($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Prayer request deleted successfully.']);
    }
}
