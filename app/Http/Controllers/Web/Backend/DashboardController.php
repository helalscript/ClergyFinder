<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\ContactUs;
use App\Models\LiveSessionRequest;
use App\Models\Newsletter;
use App\Models\PrayerRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $metrics = [
            'clergy' => User::query()->where('role', 'clergy')->count(),
            'seekers' => User::query()->where('role', 'seeker')->count(),
            'chat_rooms' => ChatRoom::query()->count(),
            'chat_messages' => ChatMessage::query()->count(),
            'blogs' => Blog::query()->count(),
            'contact_messages' => ContactUs::query()->count(),
            'prayer_requests' => PrayerRequest::query()->count(),
            'live_session_requests' => LiveSessionRequest::query()->count(),
            'newsletter_subs' => Newsletter::query()->count(),
        ];

        $chartLabels = [];
        $chartSeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->startOfDay();
            $chartLabels[] = $day->format('D');
            $chartSeries[] = ChatMessage::query()
                ->whereDate('created_at', $day->toDateString())
                ->count();
        }

        $recentRooms = ChatRoom::query()
            ->with([
                'seeker:id,name,email',
                'clergy:id,name,email',
            ])
            ->withCount('messages')
            ->latest('updated_at')
            ->limit(6)
            ->get();

        $latestMessages = ChatMessage::query()
            ->with(['room.seeker:id,name', 'room.clergy:id,name', 'sender:id,name'])
            ->latest('id')
            ->limit(8)
            ->get();

        return view('backend.layouts.dashboard.index', compact(
            'metrics',
            'chartLabels',
            'chartSeries',
            'recentRooms',
            'latestMessages'
        ));
    }
}
