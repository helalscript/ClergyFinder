<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ChatRoom::query()
                ->with([
                    'seeker:id,name,email',
                    'clergy:id,name,email',
                    'messages' => function ($q) {
                        $q->latest('id')->limit(1);
                    },
                ])
                ->latest('id');

            if ($request->filled('type') && in_array($request->type, ['prayer', 'live_session'])) {
                $query->where('type', $request->type);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('type_label', function ($row) {
                    return $row->type === 'live_session' ? 'Live Session' : 'Prayer';
                })
                ->addColumn('seeker_name', fn($row) => $row->seeker?->name ?? '-')
                ->addColumn('clergy_name', fn($row) => $row->clergy?->name ?? '-')
                ->addColumn('latest_message', fn($row) => optional($row->messages->first())->body ?? '-')
                ->addColumn('action', function ($row) {
                    return '<div class="action-wrapper">
                        <a href="' . route('admin.chats.show', $row->id) . '" class="ps-0 border-0 bg-transparent lh-1">
                            <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                        </a>
                    </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.layouts.chat.index');
    }

    public function show(int $room)
    {
        $roomData = ChatRoom::with([
            'seeker:id,name,email',
            'clergy:id,name,email',
            'prayerRequest',
            'liveSessionRequest',
            'messages' => function ($q) {
                $q->orderBy('id')->with('sender:id,name,email');
            },
        ])->findOrFail($room);

        return view('backend.layouts.chat.show', compact('roomData'));
    }
}

