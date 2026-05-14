@extends('backend.app')

@section('title')
    Dashboard
@endsection

@push('styles')
    <style>
        .dash-hero {
            background: linear-gradient(135deg, rgba(96, 93, 255, 0.08) 0%, rgba(31, 100, 241, 0.06) 50%, rgba(124, 36, 204, 0.05) 100%);
            border: 1px solid rgba(96, 93, 255, 0.12);
        }

        .dash-metric-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dash-metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.75rem 2rem rgba(15, 23, 42, 0.08);
        }

        .dash-icon-wrap {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }

        .dash-chat-scroll {
            max-height: 380px;
            overflow-y: auto;
        }

        #admin_dashboard_activity {
            min-height: 300px;
        }
    </style>
@endpush

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="card border-0 rounded-3 dash-hero mb-4">
            <div class="card-body custom-padding-30">
                <div class="row align-items-center gy-3">
                    <div class="col-lg-8">
                        <p class="text-secondary fs-13 mb-2 mb-lg-1">Admin overview</p>
                        <h3 class="fs-22 fw-semibold mb-2">
                            Welcome back,
                            <span class="text-primary">{{ Auth::user()->name ?? 'Admin' }}</span>
                        </h3>
                        <p class="text-secondary mb-0" style="max-width: 540px; line-height: 1.55;">
                            Track seekers, clergy, conversations, and requests from one place. Use the shortcuts to jump
                            into the busiest areas of your panel.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <span class="d-inline-flex align-items-center gap-2 rounded-pill bg-white px-3 py-2 border shadow-sm">
                            <i class="ri-calendar-event-line text-primary fs-18"></i>
                            <span class="fs-13 fw-medium text-secondary">{{ now()->format('l, M j, Y') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <span class="text-secondary fs-13 d-block mb-2">Clergy</span>
                                <h3 class="fw-semibold fs-24 mb-0">{{ number_format($metrics['clergy']) }}</h3>
                                <span class="badge bg-primary bg-opacity-10 text-primary mt-3 fs-12 fw-normal">App users</span>
                            </div>
                            <div class="dash-icon-wrap bg-primary bg-opacity-10 text-primary">
                                <i class="ri-user-star-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <span class="text-secondary fs-13 d-block mb-2">Seekers</span>
                                <h3 class="fw-semibold fs-24 mb-0">{{ number_format($metrics['seekers']) }}</h3>
                                <span class="badge bg-success bg-opacity-10 text-success mt-3 fs-12 fw-normal">Registered</span>
                            </div>
                            <div class="dash-icon-wrap bg-success bg-opacity-10 text-success">
                                <i class="ri-group-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <span class="text-secondary fs-13 d-block mb-2">Chat rooms</span>
                                <h3 class="fw-semibold fs-24 mb-0">{{ number_format($metrics['chat_rooms']) }}</h3>
                                <span class="badge bg-primary-div bg-opacity-10 text-primary-div mt-3 fs-12 fw-normal">Sessions</span>
                            </div>
                            <div class="dash-icon-wrap bg-primary-div bg-opacity-10 text-primary-div">
                                <i class="ri-chat-3-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex align-items-start justify-content-between gap-2">
                            <div>
                                <span class="text-secondary fs-13 d-block mb-2">Chat messages</span>
                                <h3 class="fw-semibold fs-24 mb-0">{{ number_format($metrics['chat_messages']) }}</h3>
                                <span class="badge bg-danger bg-opacity-10 text-danger mt-3 fs-12 fw-normal">All time</span>
                            </div>
                            <div class="dash-icon-wrap bg-danger bg-opacity-10 text-danger">
                                <i class="ri-message-2-line fs-22"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30 d-flex align-items-center gap-3">
                        <div class="dash-icon-wrap bg-primary bg-opacity-10 text-primary flex-shrink-0">
                            <i class="ri-article-line fs-22"></i>
                        </div>
                        <div>
                            <span class="text-secondary fs-13 d-block">Blog posts</span>
                            <h4 class="fw-semibold fs-20 mb-0">{{ number_format($metrics['blogs']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30 d-flex align-items-center gap-3">
                        <div class="dash-icon-wrap bg-warning bg-opacity-10 text-warning flex-shrink-0">
                            <i class="ri-mail-send-line fs-22"></i>
                        </div>
                        <div>
                            <span class="text-secondary fs-13 d-block">Contact messages</span>
                            <h4 class="fw-semibold fs-20 mb-0">{{ number_format($metrics['contact_messages']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30 d-flex align-items-center gap-3">
                        <div class="dash-icon-wrap bg-success bg-opacity-10 text-success flex-shrink-0">
                            <i class="ri-hand-heart-line fs-22"></i>
                        </div>
                        <div>
                            <span class="text-secondary fs-13 d-block">Prayer requests</span>
                            <h4 class="fw-semibold fs-20 mb-0">{{ number_format($metrics['prayer_requests']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30 d-flex align-items-center gap-3">
                        <div class="dash-icon-wrap bg-primary-div bg-opacity-10 text-primary-div flex-shrink-0">
                            <i class="ri-live-line fs-22"></i>
                        </div>
                        <div>
                            <span class="text-secondary fs-13 d-block">Live session requests</span>
                            <h4 class="fw-semibold fs-20 mb-0">{{ number_format($metrics['live_session_requests']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 rounded-3 bg-white mb-4 dash-metric-card">
                    <div class="card-body custom-padding-30 d-flex align-items-center gap-3">
                        <div class="dash-icon-wrap bg-primary bg-opacity-10 text-primary flex-shrink-0">
                            <i class="ri-notification-3-line fs-22"></i>
                        </div>
                        <div>
                            <span class="text-secondary fs-13 d-block">Newsletter subscribers</span>
                            <h4 class="fw-semibold fs-20 mb-0">{{ number_format($metrics['newsletter_subs']) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xxl-8 col-lg-7">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                            <div>
                                <h3 class="fs-18 fw-semibold mb-1">Chat activity</h3>
                                <p class="text-secondary fs-13 mb-0">Messages sent in the last 7 days</p>
                            </div>
                            <a href="{{ route('admin.chats.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                Open chats
                                <i class="ri-arrow-right-up-line ms-1"></i>
                            </a>
                        </div>
                        <div id="admin_dashboard_activity"></div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-5">
                <div class="card border-0 rounded-3 bg-white mb-4 h-90">
                    <div class="card-body custom-padding-10">
                        <h3 class="fs-18 fw-semibold mb-1">Shortcuts</h3>
                        <p class="text-secondary fs-13 mb-4">Frequently used admin pages</p>
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.chats.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-chat-3-line me-2 text-primary"></i> Chats</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                            <a href="{{ route('admin.prayer_requests.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-hand-heart-line me-2 text-success"></i> Prayer requests</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                            <a href="{{ route('admin.live_session_requests.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-live-line me-2 text-primary-div"></i> Live sessions</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                            <a href="{{ route('admin_contact_us.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-mail-line me-2 text-warning"></i> Contact inbox</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                            <a href="{{ route('blogs.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-article-line me-2 text-primary"></i> Blogs</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                            <a href="{{ route('admin.newsletters.index') }}" class="btn btn-light border text-start d-flex align-items-center justify-content-between rounded-3 py-2 px-3">
                                <span><i class="ri-mail-add-line me-2 text-secondary"></i> Newsletters</span>
                                <i class="ri-arrow-right-s-line text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-xl-7">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                            <div>
                                <h3 class="fs-18 fw-semibold mb-1">Recent messages</h3>
                                <p class="text-secondary fs-13 mb-0">Latest lines across all rooms</p>
                            </div>
                            <a href="{{ route('admin.chats.index') }}" class="text-primary fs-13 fw-medium text-decoration-none">
                                View all <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                        <div class="dash-chat-scroll pe-1">
                            @forelse ($latestMessages as $msg)
                                @php
                                    $room = $msg->room;
                                    $pair = $room
                                        ? trim(($room->seeker->name ?? 'Seeker') . ' · ' . ($room->clergy->name ?? 'Clergy'))
                                        : 'Room';
                                @endphp
                                <div class="d-flex gap-3 pb-3 mb-3 border-bottom border-opacity-10">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="ri-user-voice-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                            <span class="fw-medium fs-14">{{ $msg->sender->name ?? 'User' }}</span>
                                            <span class="badge bg-body-secondary text-secondary fs-11 fw-normal">{{ $pair }}</span>
                                        </div>
                                        <p class="text-secondary fs-13 mb-1" style="line-height: 1.45;">
                                            {{ \Illuminate\Support\Str::limit($msg->body, 120) }}
                                        </p>
                                        <span class="text-body-secondary fs-12">{{ $msg->created_at?->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-secondary py-5">
                                    <i class="ri-chat-off-line fs-36 d-block mb-2 opacity-50"></i>
                                    No messages yet. Chats will appear here once users start conversations.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card border-0 rounded-3 bg-white mb-4">
                    <div class="card-body custom-padding-30">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                            <div>
                                <h3 class="fs-18 fw-semibold mb-1">Active rooms</h3>
                                <p class="text-secondary fs-13 mb-0">Recently updated conversations</p>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="border-bottom">
                                    <tr>
                                        <th class="text-secondary fw-medium fs-12 border-0 pb-2">Room</th>
                                        <th class="text-secondary fw-medium fs-12 border-0 pb-2 text-end">Msgs</th>
                                        <th class="text-secondary fw-medium fs-12 border-0 pb-2 text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentRooms as $room)
                                        <tr>
                                            <td class="border-0 py-3">
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium fs-13">
                                                        {{ $room->seeker->name ?? '—' }}
                                                        <span class="text-secondary fw-normal">→</span>
                                                        {{ $room->clergy->name ?? '—' }}
                                                    </span>
                                                    <span class="fs-12 text-secondary">
                                                        {{ $room->type === 'live_session' ? 'Live session' : 'Prayer' }}
                                                        · {{ $room->updated_at?->diffForHumans() }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="border-0 py-3 text-end">
                                                <span class="badge bg-primary bg-opacity-10 text-primary fs-12 fw-normal">
                                                    {{ number_format($room->messages_count) }}
                                                </span>
                                            </td>
                                            <td class="border-0 py-3 text-end">
                                                <a href="{{ route('admin.chats.show', $room->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Open
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-secondary py-4 border-0">
                                                No chat rooms yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.querySelector('#admin_dashboard_activity');
            if (!el || typeof ApexCharts === 'undefined') {
                return;
            }
            var labels = @json($chartLabels);
            var seriesData = @json($chartSeries);
            var options = {
                series: [{
                    name: 'Messages',
                    data: seriesData
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit'
                },
                colors: ['#605DFF'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.35,
                        opacityTo: 0.05,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#EEF2F7',
                    strokeDashArray: 4,
                    padding: {
                        top: 0,
                        right: 8,
                        bottom: 0,
                        left: 8
                    }
                },
                xaxis: {
                    categories: labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#8695AA',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#8695AA',
                            fontSize: '12px'
                        },
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    },
                    min: 0,
                    forceNiceScale: true
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + ' messages';
                        }
                    }
                },
                markers: {
                    size: 4,
                    colors: ['#605DFF'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 6
                    }
                }
            };
            var chart = new ApexCharts(el, options);
            chart.render();
        });
    </script>
@endpush
