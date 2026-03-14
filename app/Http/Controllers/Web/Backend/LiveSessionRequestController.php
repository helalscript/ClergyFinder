<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\LiveSessionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LiveSessionRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LiveSessionRequest::with(['seeker:id,name,email', 'clergy:id,name,email'])->latest();

            if ($request->filled('status') && in_array($request->status, ['pending', 'scheduled', 'completed'])) {
                $query->where('status', $request->status);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('seeker_name', fn($row) => $row->seeker?->name ?? '-')
                ->addColumn('clergy_name', fn($row) => $row->clergy?->name ?? '-')
                ->addColumn('message_preview', fn($row) => \Str::limit($row->message, 50))
                ->addColumn('scheduled_time', fn($row) => $row->scheduled_time?->format('M j, Y g:i A') ?? '-')
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a href="' . route('admin.live_session_requests.show', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1"><i class="material-symbols-outlined fs-16 text-primary">visibility</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1" onclick="deleteRecord(event,' . $row->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.live_session_requests.index');
    }

    public function show(int $id)
    {
        $data = LiveSessionRequest::with(['seeker', 'clergy'])->findOrFail($id);
        $clergyList = User::where('role', 'clergy')->whereHas('clergy', fn($q) => $q->where('is_live_session', true))->get(['id', 'name', 'email']);
        return view('backend.layouts.live_session_requests.show', compact('data', 'clergyList'));
    }

    public function assignClergy(Request $request, int $id)
    {
        $validated = Validator::make($request->all(), [
            'clergy_id' => 'required|exists:users,id',
        ])->validate();

        $liveSessionRequest = LiveSessionRequest::findOrFail($id);
        $liveSessionRequest->update(['clergy_id' => $validated['clergy_id']]);

        return redirect()->route('admin.live_session_requests.show', $id)->with('success', 'Clergy assigned successfully.');
    }

    public function markCompleted(int $id)
    {
        $liveSessionRequest = LiveSessionRequest::findOrFail($id);
        $liveSessionRequest->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Live session marked as completed.');
    }

    public function destroy(int $id)
    {
        $data = LiveSessionRequest::findOrFail($id);
        $data->delete();
        return response()->json(['success' => true, 'message' => 'Live session request deleted successfully.']);
    }
}
