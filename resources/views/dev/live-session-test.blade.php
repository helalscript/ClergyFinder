<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Session API & Agora Testbed</title>
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome for Premium Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Agora RTC Web SDK -->
    <script src="https://download.agora.io/sdk/release/AgoraRTC_N-4.20.0.js"></script>
    
    <!-- Pusher & Laravel Echo CDNs -->
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.0/dist/echo.iife.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top, #0f172a, #020617);
            color: #f8fafc;
            min-height: 100vh;
        }
        
        .glass-panel {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        
        .glass-panel-heavy {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .text-gradient-seeker {
            background: linear-gradient(135deg, #34d399, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-clergy {
            background: linear-gradient(135deg, #a78bfa, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .tab-glow-seeker {
            box-shadow: 0 0 15px rgba(6, 182, 212, 0.15);
        }
        
        .tab-glow-clergy {
            box-shadow: 0 0 15px rgba(236, 72, 153, 0.15);
        }

        /* Custom Scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .video-container {
            background: #090d16;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 0.75rem;
            position: relative;
            overflow: hidden;
            aspect-ratio: 4/3;
        }

        .video-container div {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
</head>
<body class="p-4 md:p-6 lg:p-8">

    <!-- Top Navigation / Brand -->
    <header class="max-w-7xl mx-auto mb-6 flex flex-col md:flex-row items-center justify-between gap-4 glass-panel p-4 rounded-2xl">
        <div class="flex items-center gap-3">
            <div class="bg-gradient-to-tr from-indigo-500 to-pink-500 p-2.5 rounded-xl shadow-lg">
                <i class="fa-solid fa-circle-play text-xl text-white"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold tracking-tight">Live Session Integration Testbed</h1>
                <p class="text-xs text-slate-400">Pair-testing API endpoints & Agora Web SDK streams side-by-side</p>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs px-2.5 py-1 bg-amber-500/10 text-amber-400 border border-amber-500/20 rounded-full font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Developer Mode
            </span>
            <button onclick="loadUsers()" class="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-semibold transition-all">
                <i class="fa-solid fa-rotate mr-1.5"></i> Refresh User Lists
            </button>
        </div>
    </header>

    <!-- Main Workspace Split Panel -->
    <main class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- SEEKER SIDE (LEFT) -->
        <section class="glass-panel rounded-2xl overflow-hidden tab-glow-seeker border-t-2 border-t-cyan-500 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-slate-800 bg-slate-900/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse"></span>
                    <h2 class="text-lg font-bold text-gradient-seeker">Seeker Control Room</h2>
                </div>
                <div id="seeker-badge" class="hidden text-xs font-medium px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    Logged In
                </div>
            </div>

            <!-- Panel Content -->
            <div class="p-5 flex-1 space-y-6">
                <!-- Seeker Auth -->
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Authentication</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <select id="seeker-select" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-cyan-500">
                            <option value="">Select a Seeker...</option>
                        </select>
                        <div class="flex gap-2">
                            <button onclick="loginAs('seeker')" class="flex-1 bg-cyan-600 hover:bg-cyan-500 text-white font-medium text-sm py-2 px-3 rounded-xl transition-all shadow-md shadow-cyan-900/20">
                                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Log In
                            </button>
                            <button onclick="createTestUser('seeker')" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-sm p-2 rounded-xl transition-all" title="Create New Seeker">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="seeker-user-info" class="hidden text-xs text-cyan-400/90 font-medium"></div>
                </div>

                <!-- Seeker API Queue actions -->
                <div class="space-y-4 pt-4 border-t border-slate-800/60">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Queue Controls</label>
                    
                    <!-- Join Queue Form -->
                    <div class="space-y-3 p-4 bg-slate-900/40 rounded-xl border border-slate-800/80">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-1 uppercase">Target Clergy ID</label>
                                <select id="seeker-clergy-select" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-cyan-500">
                                    <option value="">Choose Clergy...</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] text-slate-500 font-semibold mb-1 uppercase font-mono">Heartbeat Status</label>
                                <div class="flex items-center gap-2 h-8 text-xs">
                                    <span id="seeker-heartbeat-dot" class="w-2 h-2 rounded-full bg-slate-600"></span>
                                    <span id="seeker-heartbeat-text" class="text-slate-400 font-medium">Idle</span>
                                    <button id="seeker-heartbeat-btn" onclick="sendSeekerHeartbeat()" class="hidden ml-auto text-[10px] text-cyan-400 hover:underline">
                                        <i class="fa-solid fa-heartbeat mr-0.5"></i> Ping
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] text-slate-500 font-semibold mb-1 uppercase">Queue Message (Optional)</label>
                            <input id="seeker-queue-message" type="text" placeholder="I need guidance..." class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-1.5 text-xs text-slate-200 outline-none focus:border-cyan-500">
                        </div>
                        <div class="flex gap-2 pt-1">
                            <button id="seeker-join-btn" onclick="joinQueue()" class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                                <i class="fa-solid fa-users mr-1"></i> Join Live Queue
                            </button>
                            <button id="seeker-leave-btn" onclick="leaveQueue()" class="hidden bg-rose-950/40 text-rose-400 border border-rose-900/30 hover:bg-rose-950/70 font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                                <i class="fa-solid fa-right-from-bracket mr-1"></i> Leave Queue
                            </button>
                        </div>
                    </div>

                    <!-- Queue Status & Confirm Ready Screen -->
                    <div id="seeker-queue-card" class="hidden p-4 rounded-xl border transition-all">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 id="seeker-queue-title" class="font-bold text-sm text-white">Queued</h3>
                                <p id="seeker-queue-desc" class="text-xs text-slate-400 mt-1">Waiting in line...</p>
                            </div>
                            <button onclick="refreshSeekerStatus()" class="text-xs px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded border border-slate-700">
                                <i class="fa-solid fa-sync mr-1"></i> Check API
                            </button>
                        </div>
                        
                        <!-- Confirmation Action (Shown only when awaiting_confirm) -->
                        <div id="seeker-confirm-ready-container" class="hidden mt-4 p-3 bg-cyan-950/40 border border-cyan-800/40 rounded-lg text-center animate-pulse">
                            <p class="text-xs text-cyan-300 font-semibold mb-2.5">Your turn has arrived! Confirm readiness within 10 minutes.</p>
                            <button onclick="confirmReady()" class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-bold text-xs py-2 px-4 rounded-md shadow-lg shadow-cyan-500/20 uppercase tracking-wider">
                                <i class="fa-solid fa-circle-check mr-1.5"></i> I am Ready
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Seeker Video stream containers -->
                <div class="space-y-3 pt-4 border-t border-slate-800/60">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Video Call (Agora)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Local Feed (Camera)</span>
                            <div id="seeker-local" class="video-container flex items-center justify-center text-slate-500 text-xs">
                                <i class="fa-solid fa-video-slash text-lg mb-1"></i> Local Preview
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Remote Feed (Clergy)</span>
                            <div id="seeker-remote" class="video-container flex items-center justify-center text-slate-500 text-xs">
                                <i class="fa-solid fa-user-circle text-lg mb-1"></i> Waiting for Clergy
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button id="seeker-agora-btn" onclick="manualJoinAgora('seeker')" class="hidden flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                            <i class="fa-solid fa-phone mr-1"></i> Force Connect Agora
                        </button>
                        <button id="seeker-hangup-btn" onclick="hangupAgora('seeker')" class="hidden bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                            <i class="fa-solid fa-phone-slash mr-1"></i> Hang Up
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Panel Footer Logs -->
            <div class="p-3 bg-slate-950/80 border-t border-slate-900 h-28 overflow-y-auto font-mono text-[10px] text-slate-400" id="seeker-logs">
                <span class="text-slate-500">[System] Initialized Seeker panel. Log in to start.</span>
            </div>
        </section>

        <!-- CLERGY SIDE (RIGHT) -->
        <section class="glass-panel rounded-2xl overflow-hidden tab-glow-clergy border-t-2 border-t-pink-500 flex flex-col">
            <!-- Header -->
            <div class="p-4 border-b border-slate-800 bg-slate-900/30 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-pink-500 animate-pulse"></span>
                    <h2 class="text-lg font-bold text-gradient-clergy">Clergy Dashboard</h2>
                </div>
                <div id="clergy-badge" class="hidden text-xs font-medium px-2 py-0.5 rounded bg-pink-500/10 text-pink-400 border border-pink-500/20">
                    Logged In
                </div>
            </div>

            <!-- Panel Content -->
            <div class="p-5 flex-1 space-y-6">
                <!-- Clergy Auth -->
                <div class="space-y-3">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Authentication</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <select id="clergy-select" class="bg-slate-950 border border-slate-800 rounded-xl px-3 py-2 text-sm text-slate-200 outline-none focus:border-pink-500">
                            <option value="">Select a Clergy...</option>
                        </select>
                        <div class="flex gap-2">
                            <button onclick="loginAs('clergy')" class="flex-1 bg-pink-600 hover:bg-pink-500 text-white font-medium text-sm py-2 px-3 rounded-xl transition-all shadow-md shadow-pink-900/20">
                                <i class="fa-solid fa-right-to-bracket mr-1.5"></i> Log In
                            </button>
                            <button onclick="createTestUser('clergy')" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium text-sm p-2 rounded-xl transition-all" title="Create New Clergy">
                                <i class="fa-solid fa-user-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div id="clergy-user-info" class="hidden text-xs text-pink-400/90 font-medium flex justify-between items-center">
                        <span id="clergy-user-info-text"></span>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-400">Live Status:</span>
                            <button id="clergy-avail-toggle" onclick="toggleClergyAvailability()" class="px-2 py-0.5 bg-slate-800 hover:bg-slate-700 rounded text-[10px] font-bold border border-slate-700 text-slate-300"></button>
                        </div>
                    </div>
                </div>

                <!-- Clergy Live Session Actions & Queue Status -->
                <div class="space-y-4 pt-4 border-t border-slate-800/60">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Queue & Active Request</label>
                        <button id="clergy-refresh-btn" onclick="fetchClergyQueue()" class="hidden text-xs text-pink-400 hover:underline">
                            <i class="fa-solid fa-sync mr-1"></i> Refresh Queue
                        </button>
                    </div>

                    <!-- Active Session Card -->
                    <div id="clergy-active-card" class="p-4 bg-slate-900/40 rounded-xl border border-slate-800/80 space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="font-bold text-sm text-slate-200">Current Session</h3>
                                <p id="clergy-active-desc" class="text-xs text-slate-500 mt-0.5">No active seeker ready yet.</p>
                            </div>
                            <span id="clergy-active-status" class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700">IDLE</span>
                        </div>
                        
                        <div id="clergy-active-details" class="hidden text-xs space-y-1.5 p-2.5 bg-slate-950/50 rounded-lg">
                            <div class="text-slate-300 font-medium"><i class="fa-solid fa-user mr-1.5 text-pink-400"></i> Seeker: <span id="clergy-active-seeker-name">...</span></div>
                            <div class="text-slate-400 text-[11px]"><i class="fa-solid fa-envelope mr-1.5 text-slate-500"></i> Email: <span id="clergy-active-seeker-email">...</span></div>
                            <div class="text-slate-400 text-[11px]"><i class="fa-solid fa-message mr-1.5 text-slate-500"></i> Msg: <span id="clergy-active-message">...</span></div>
                        </div>

                        <!-- Clergy Call buttons -->
                        <div class="flex gap-2">
                            <button id="clergy-notify-btn" onclick="notifyNextSeeker()" class="hidden flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                                <i class="fa-solid fa-bell mr-1"></i> Notify / Promote Next Seeker
                            </button>
                            <button id="clergy-start-btn" onclick="startVideoCall()" class="hidden flex-1 bg-pink-600 hover:bg-pink-500 text-white font-bold text-xs py-2 px-3 rounded-lg transition-all shadow-md shadow-pink-900/10">
                                <i class="fa-solid fa-video mr-1"></i> Start Video Call
                            </button>
                            <button id="clergy-complete-btn" onclick="completeVideoCall()" class="hidden flex-1 bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs py-2 px-3 rounded-lg transition-all">
                                <i class="fa-solid fa-square-check mr-1 text-white"></i> Complete Call
                            </button>
                        </div>
                        
                        <!-- Reject current session option -->
                        <div id="clergy-reject-box" class="hidden pt-3 border-t border-slate-800/80 space-y-2">
                            <label class="block text-[10px] text-slate-500 font-bold uppercase tracking-wide">Reject Current Session</label>
                            <div class="flex gap-2">
                                <input id="clergy-reject-reason" type="text" placeholder="Reason (e.g. emergency)..." class="flex-1 bg-slate-950 border border-slate-800 rounded-lg px-2.5 py-1.5 text-xs text-slate-200 outline-none focus:border-pink-500">
                                <button onclick="rejectActiveSession()" class="bg-rose-950/40 text-rose-400 border border-rose-900/30 hover:bg-rose-950/70 font-semibold text-xs py-1.5 px-3 rounded-lg transition-all">
                                    <i class="fa-solid fa-ban mr-1"></i> Reject
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Queue List -->
                    <div id="clergy-queue-list-container" class="hidden space-y-2">
                        <h4 class="text-[10px] text-slate-500 font-bold uppercase tracking-wide">Waiting Queue (<span id="clergy-queue-count">0</span>)</h4>
                        <div id="clergy-queue-items" class="space-y-1.5 max-h-36 overflow-y-auto pr-1">
                            <div class="text-center text-xs text-slate-600 py-2 border border-dashed border-slate-800 rounded-lg">Queue is empty</div>
                        </div>
                    </div>
                </div>

                <!-- Clergy Video stream containers -->
                <div class="space-y-3 pt-4 border-t border-slate-800/60">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Video Call (Agora)</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Local Feed (Camera)</span>
                            <div id="clergy-local" class="video-container flex items-center justify-center text-slate-500 text-xs">
                                <i class="fa-solid fa-video-slash text-lg mb-1"></i> Local Preview
                            </div>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-500 font-bold mb-1 uppercase">Remote Feed (Seeker)</span>
                            <div id="clergy-remote" class="video-container flex items-center justify-center text-slate-500 text-xs">
                                <i class="fa-solid fa-user-circle text-lg mb-1"></i> Waiting for Seeker
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button id="clergy-agora-btn" onclick="manualJoinAgora('clergy')" class="hidden flex-1 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                            <i class="fa-solid fa-phone mr-1"></i> Force Connect Agora
                        </button>
                        <button id="clergy-hangup-btn" onclick="hangupAgora('clergy')" class="hidden bg-rose-600 hover:bg-rose-500 text-white font-semibold text-xs py-2 px-3 rounded-lg transition-all">
                            <i class="fa-solid fa-phone-slash mr-1"></i> Hang Up
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Panel Footer Logs -->
            <div class="p-3 bg-slate-950/80 border-t border-slate-900 h-28 overflow-y-auto font-mono text-[10px] text-slate-400" id="clergy-logs">
                <span class="text-slate-500">[System] Initialized Clergy panel. Log in to start.</span>
            </div>
        </section>

    </main>

    <!-- Global Logs & Debug Console at Bottom -->
    <footer class="max-w-7xl mx-auto mt-6 glass-panel rounded-2xl overflow-hidden border border-slate-800">
        <div class="bg-slate-950/60 p-3 px-4 flex items-center justify-between border-b border-slate-900">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-terminal text-xs text-indigo-400"></i>
                <span class="text-xs font-semibold text-slate-300">Global Activity Console</span>
            </div>
            <button onclick="clearGlobalConsole()" class="text-[10px] text-slate-500 hover:text-slate-300">
                <i class="fa-solid fa-trash-can mr-1"></i> Clear Console
            </button>
        </div>
        <div id="global-console" class="p-4 h-48 overflow-y-auto font-mono text-xs text-slate-400 bg-slate-950/90 space-y-1 select-text">
            <div class="text-slate-600">[System] Console ready... Select test accounts above to begin testing.</div>
        </div>
    </footer>

    <!-- Javascript integration -->
    <script>
        // Server variables injected by Blade
        const AGORA_APP_ID = "{{ config('agora.app_id') }}";
        const BROADCAST_CONNECTION = "{{ env('BROADCAST_CONNECTION', 'reverb') }}";
        const REVERB_APP_KEY = "{{ env('REVERB_APP_KEY') }}";
        const REVERB_HOST = "{{ env('REVERB_HOST') }}";
        const REVERB_PORT = "{{ env('REVERB_PORT') }}";
        const REVERB_SCHEME = "{{ env('REVERB_SCHEME') }}";
        const PUSHER_APP_KEY = "{{ env('PUSHER_APP_KEY') }}";
        const PUSHER_APP_CLUSTER = "{{ env('PUSHER_APP_CLUSTER') }}";

        // Global states
        const state = {
            seeker: {
                token: null,
                userId: null,
                userEmail: null,
                userName: null,
                activeRequest: null,
                heartbeatInterval: null,
                heartbeatFailCount: 0,
                echo: null,
                agora: null // AgoraClientHelper instance
            },
            clergy: {
                token: null,
                userId: null,
                userEmail: null,
                userName: null,
                clergyId: null,
                isAvailable: false,
                activeRequest: null, // current_session
                echo: null,
                agora: null, // AgoraClientHelper instance
                queuePollInterval: null
            }
        };

        // Initialize helper class for Agora RTC
        class AgoraClientHelper {
            constructor(role, localContainerId, remoteContainerId) {
                this.role = role;
                this.localContainerId = localContainerId;
                this.remoteContainerId = remoteContainerId;
                this.client = null;
                this.localAudioTrack = null;
                this.localVideoTrack = null;
                this.isJoined = false;
            }

            async join(appId, channel, token, uid) {
                this.log(`Joining Agora channel "${channel}" with Token (starts ${token ? token.substring(0, 10) : 'null'}...) and UID ${uid}...`);
                
                await this.leave(); // Clean up if any

                this.client = AgoraRTC.createClient({ mode: "rtc", codec: "vp8" });

                // Subscribe events
                this.client.on("user-published", async (user, mediaType) => {
                    this.log(`Remote participant (${user.uid}) published ${mediaType}`);
                    await this.client.subscribe(user, mediaType);
                    
                    if (mediaType === "video") {
                        const remoteVideoTrack = user.videoTrack;
                        const container = document.getElementById(this.remoteContainerId);
                        container.innerHTML = "";
                        remoteVideoTrack.play(container);
                        this.log(`Playing remote video stream.`);
                    }
                    if (mediaType === "audio") {
                        user.audioTrack.play();
                        this.log(`Playing remote audio stream.`);
                    }
                });

                this.client.on("user-unpublished", (user) => {
                    this.log(`Remote participant (${user.uid}) stopped broadcasting`);
                    const container = document.getElementById(this.remoteContainerId);
                    container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-500 text-xs">
                        <i class="fa-solid fa-video-slash text-lg mb-1"></i> Remote stream stopped
                    </div>`;
                });

                try {
                    await this.client.join(appId, channel, token, Number(uid));
                    this.isJoined = true;
                    this.log(`Joined channel successfully.`);
                    
                    // Create tracks
                    this.log(`Requesting camera/microphone tracks...`);
                    try {
                        const [audioTrack, videoTrack] = await AgoraRTC.createMicrophoneAndCameraTracks();
                        this.localAudioTrack = audioTrack;
                        this.localVideoTrack = videoTrack;

                        const localContainer = document.getElementById(this.localContainerId);
                        localContainer.innerHTML = "";
                        this.localVideoTrack.play(localContainer);

                        await this.client.publish([this.localAudioTrack, this.localVideoTrack]);
                        this.log(`Successfully published local audio/video tracks.`);
                    } catch (deviceErr) {
                        this.log(`Access to camera/microphone denied or unavailable. Running in subscribe-only mode: ${deviceErr.message}`, true);
                        const localContainer = document.getElementById(this.localContainerId);
                        localContainer.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-amber-500 text-xs p-2 text-center">
                            <i class="fa-solid fa-triangle-exclamation text-lg mb-1"></i> Subscribe-only (No Mic/Cam)
                        </div>`;
                    }

                    // Show hangup button, hide join button
                    document.getElementById(`${this.role}-agora-btn`).classList.add('hidden');
                    document.getElementById(`${this.role}-hangup-btn`).classList.remove('hidden');

                } catch (err) {
                    this.log(`Error during Agora join: ${err.message}`, true);
                    alert(`Agora connection failed: ${err.message}`);
                }
            }

            async leave() {
                if (this.localAudioTrack) {
                    this.localAudioTrack.close();
                    this.localAudioTrack = null;
                }
                if (this.localVideoTrack) {
                    this.localVideoTrack.close();
                    this.localVideoTrack = null;
                }
                if (this.client) {
                    try {
                        await this.client.leave();
                    } catch (e) {
                        this.log(`Err leaving: ${e.message}`);
                    }
                    this.client = null;
                    this.log(`Disconnected from Agora.`);
                }
                this.isJoined = false;
                
                document.getElementById(this.localContainerId).innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-500 text-xs">
                    <i class="fa-solid fa-video-slash text-lg mb-1"></i> Local camera feed
                </div>`;
                document.getElementById(this.remoteContainerId).innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-500 text-xs">
                    <i class="fa-solid fa-user-circle text-lg mb-1"></i> Waiting for stream...
                </div>`;

                document.getElementById(`${this.role}-agora-btn`).classList.add('hidden');
                document.getElementById(`${this.role}-hangup-btn`).classList.add('hidden');
            }

            log(msg, isError = false) {
                log(this.role, `[Agora] ${msg}`, null, isError);
            }
        }

        // Initialize custom helpers
        state.seeker.agora = new AgoraClientHelper('seeker', 'seeker-local', 'seeker-remote');
        state.clergy.agora = new AgoraClientHelper('clergy', 'clergy-local', 'clergy-remote');

        // Global Logger
        function log(side, message, data = null, isError = false) {
            const timestamp = new Date().toLocaleTimeString();
            const colorClass = isError ? 'text-red-400' : (side === 'seeker' ? 'text-cyan-300' : (side === 'clergy' ? 'text-pink-300' : 'text-slate-300'));
            const sideLabel = side ? `[${side.toUpperCase()}] ` : '';
            
            // Console log
            console.log(`[${timestamp}] ${sideLabel}${message}`, data || '');

            // Sidebar local log
            if (side && (side === 'seeker' || side === 'clergy')) {
                const sidebarLog = document.getElementById(`${side}-logs`);
                if (sidebarLog) {
                    const logEl = document.createElement('div');
                    logEl.className = isError ? 'text-red-400 border-l border-red-500 pl-1.5 my-0.5' : 'my-0.5';
                    logEl.innerText = `[${timestamp}] ${message}`;
                    sidebarLog.appendChild(logEl);
                    sidebarLog.scrollTop = sidebarLog.scrollHeight;
                }
            }

            // Global bottom log
            const globalConsole = document.getElementById('global-console');
            if (globalConsole) {
                const logLine = document.createElement('div');
                logLine.className = 'flex items-start gap-1 py-0.5 hover:bg-slate-900 rounded px-1 transition-all';
                
                let dataStr = '';
                if (data) {
                    try {
                        dataStr = ` <span class="text-slate-500 font-mono text-[10px]">(${JSON.stringify(data)})</span>`;
                    } catch (e) {
                        dataStr = ' (complex data)';
                    }
                }
                
                logLine.innerHTML = `<span class="text-slate-600">[${timestamp}]</span> <span class="${colorClass} font-semibold">${sideLabel}</span><span class="text-slate-300">${message}</span>${dataStr}`;
                globalConsole.appendChild(logLine);
                globalConsole.scrollTop = globalConsole.scrollHeight;
            }
        }

        function clearGlobalConsole() {
            document.getElementById('global-console').innerHTML = '<div class="text-slate-600">[System] Console cleared.</div>';
        }

        // Initialize Echo/Reverb channels
        function initEchoConnection(role, token, userId, onNotification) {
            if (typeof Pusher === 'undefined' || typeof Echo === 'undefined') {
                log(role, 'Pusher/Echo libraries not loaded.', null, true);
                return null;
            }

            try {
                // Setup dual separate Echo connections.
                const options = {
                    broadcaster: BROADCAST_CONNECTION === 'reverb' ? 'reverb' : 'pusher',
                    key: BROADCAST_CONNECTION === 'reverb' ? REVERB_APP_KEY : PUSHER_APP_KEY,
                    wsHost: BROADCAST_CONNECTION === 'reverb' ? REVERB_HOST : undefined,
                    wsPort: BROADCAST_CONNECTION === 'reverb' ? (parseInt(REVERB_PORT) || 80) : undefined,
                    wssPort: BROADCAST_CONNECTION === 'reverb' ? (parseInt(REVERB_PORT) || 443) : undefined,
                    forceTLS: BROADCAST_CONNECTION === 'reverb' ? (REVERB_SCHEME === 'https') : true,
                    cluster: BROADCAST_CONNECTION !== 'reverb' ? PUSHER_APP_CLUSTER : undefined,
                    enabledTransports: ['ws', 'wss'],
                    authEndpoint: '/api/broadcasting/auth',
                    auth: {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    }
                };

                const echoInstance = new Echo(options);
                log(role, `Initializing Echo client. Subscribing to private channel: private-App.Models.User.${userId}`);

                echoInstance.private(`App.Models.User.${userId}`)
                    .notification((notification) => {
                        log(role, `⚡ BROADCAST RECEIVED: type="${notification.type}"`, notification);
                        onNotification(notification);
                    });

                // Bind connection state events
                echoInstance.connector.pusher.connection.bind('connected', () => {
                    log(role, '📡 Echo WebSocket connected successfully.');
                });
                echoInstance.connector.pusher.connection.bind('failed', () => {
                    log(role, '📡 Echo WebSocket connection failed.', null, true);
                });
                echoInstance.connector.pusher.connection.bind('disconnected', () => {
                    log(role, '📡 Echo WebSocket disconnected.');
                });

                return echoInstance;
            } catch (err) {
                log(role, `Echo initialization failed: ${err.message}`, null, true);
                return null;
            }
        }

        // On Page Load
        document.addEventListener('DOMContentLoaded', () => {
            loadUsers();
            if (!AGORA_APP_ID) {
                log(null, '⚠️ AGORA_APP_ID is not configured in .env. Video calls will fall back to simulation.', null, true);
            }
        });

        // Load Seekers & Clergies from API
        async function loadUsers() {
            log(null, 'Fetching seekers and clergy list...');
            try {
                const response = await fetch('/dev/live-session-test/users');
                const result = await response.json();
                
                if (result.status) {
                    const seekerSelect = document.getElementById('seeker-select');
                    const clergySelect = document.getElementById('clergy-select');
                    const seekerClergySelect = document.getElementById('seeker-clergy-select');

                    // Reset
                    seekerSelect.innerHTML = '<option value="">Select a Seeker...</option>';
                    clergySelect.innerHTML = '<option value="">Select a Clergy...</option>';
                    seekerClergySelect.innerHTML = '<option value="">Choose Clergy...</option>';

                    // Populate Seekers
                    result.seekers.forEach(user => {
                        const option = `<option value="${user.id}">${user.name} (${user.email})</option>`;
                        seekerSelect.innerHTML += option;
                    });

                    // Populate Clergies
                    result.clergy.forEach(user => {
                        const isApproved = user.clergy && user.clergy.registration_status === 'approved';
                        const label = isApproved ? '' : ' [PENDING APPROVAL]';
                        const option = `<option value="${user.id}">${user.name} (${user.email})${label}</option>`;
                        
                        clergySelect.innerHTML += option;
                        
                        // Populate seeker selection dropdown too
                        if (user.clergy) {
                            const seekerClergyOption = `<option value="${user.clergy.id}">${user.name} (${user.email})</option>`;
                            seekerClergySelect.innerHTML += seekerClergyOption;
                        }
                    });

                    log(null, `Fetched ${result.seekers.length} seekers and ${result.clergy.length} clergy users.`);
                }
            } catch (error) {
                log(null, `Error loading users: ${error.message}`, null, true);
            }
        }

        // Create random test user
        async function createTestUser(role) {
            log(role, `Auto-creating new test ${role} account...`);
            try {
                const response = await fetch('/dev/live-session-test/create-user', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ role: role })
                });
                const result = await response.json();
                
                if (result.status) {
                    log(role, `New test account created: ID ${result.user.id}, email: ${result.user.email}`);
                    await loadUsers(); // Refresh lists
                    
                    // Auto select the new user
                    const select = document.getElementById(`${role}-select`);
                    select.value = result.user.id;
                    
                    // Log in
                    handleAuthSuccess(role, result.token, result.user);
                } else {
                    log(role, `Create test user failed: ${result.message}`, null, true);
                }
            } catch (error) {
                log(role, `Error creating test user: ${error.message}`, null, true);
            }
        }

        // Log in to selected user
        async function loginAs(role) {
            const select = document.getElementById(`${role}-select`);
            const userId = select.value;
            if (!userId) {
                alert(`Please select a ${role} user first.`);
                return;
            }

            log(role, `Logging in bypassing credentials for User ID: ${userId}...`);
            try {
                const response = await fetch('/dev/live-session-test/quick-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ user_id: userId })
                });
                const result = await response.json();
                
                if (result.status) {
                    handleAuthSuccess(role, result.token, result.user);
                } else {
                    log(role, `Bypass authentication failed: ${result.message}`, null, true);
                    alert(`Login failed: ${result.message}`);
                }
            } catch (error) {
                log(role, `Login Error: ${error.message}`, null, true);
            }
        }

        // Handle successful auth
        function handleAuthSuccess(role, token, user) {
            state[role].token = token;
            state[role].userId = user.id;
            state[role].userEmail = user.email;
            state[role].userName = user.name;

            // Show logged-in badge
            document.getElementById(`${role}-badge`).classList.remove('hidden');
            
            // Display User info
            const infoDiv = document.getElementById(`${role}-user-info`);
            infoDiv.classList.remove('hidden');
            
            if (role === 'seeker') {
                infoDiv.innerHTML = `<i class="fa-solid fa-user-circle mr-1"></i> Authenticated as: <strong>${user.name}</strong>`;
                log(role, `JWT token received. Ready to join queues. Token starts with: ${token.substring(0, 15)}...`);
            } else {
                state[role].clergyId = user.clergy ? user.clergy.id : null;
                document.getElementById('clergy-user-info-text').innerHTML = `<i class="fa-solid fa-user-tie mr-1"></i> Clergy: <strong>${user.name}</strong>`;
                
                // Set availability status in UI
                state[role].isAvailable = user.clergy ? !!user.clergy.is_available_for_live : false;
                updateClergyAvailUI();
                
                // Show notify & refresh queue actions
                document.getElementById('clergy-refresh-btn').classList.remove('hidden');
                document.getElementById('clergy-notify-btn').classList.remove('hidden');
                document.getElementById('clergy-queue-list-container').classList.remove('hidden');
                
                log(role, `JWT token received. Clergy ID: ${state[role].clergyId}. Ready to manage live calls.`);
                
                // Start polling queue snapshot in background
                if (state.clergy.queuePollInterval) clearInterval(state.clergy.queuePollInterval);
                state.clergy.queuePollInterval = setInterval(fetchClergyQueue, 15000);
                fetchClergyQueue();
            }

            // Init Laravel Echo broadcast listener
            if (state[role].echo) {
                state[role].echo.disconnect();
            }
            state[role].echo = initEchoConnection(role, token, user.id, (notification) => {
                handleBroadcastNotification(role, notification);
            });
        }

        // Handle incoming Broadcast notifications
        function handleBroadcastNotification(role, notification) {
            if (role === 'seeker') {
                // Seeker receives a notification
                if (notification.type === 'live_session_your_turn') {
                    log('seeker', '🚨 YOUR TURN! Seeker promoted from queue.');
                    refreshSeekerStatus();
                } else if (notification.type === 'live_session_clergy_started_video') {
                    log('seeker', '🚨 Clergy started the call! Joining Agora channel...');
                    
                    const agoraJoin = notification.agora_join || null;
                    if (agoraJoin) {
                        joinAgoraCall('seeker', agoraJoin.channel, agoraJoin.token, agoraJoin.uid);
                    } else {
                        log('seeker', 'Warning: Seeker received video started but no Agora credentials included. Trying to query join API...', null, true);
                        queryAgoraJoinCredentials('seeker', notification.request_id);
                    }
                } else if (notification.type === 'live_session_queue_abandoned') {
                    log('seeker', '🚨 Removed from queue due to missing heartbeat/inactivity.', null, true);
                    resetSeekerState();
                } else if (notification.type === 'live_session_rejected') {
                    log('seeker', `🚨 REJECTED BY CLERGY! Reason: "${notification.message}"`, null, true);
                    alert(`You were removed from the queue by the clergy.\nReason: ${notification.message}`);
                    resetSeekerState();
                }
            } else {
                // Clergy receives a notification
                if (notification.type === 'live_session_queue_join') {
                    log('clergy', '🔔 A new seeker has joined the live queue.');
                    fetchClergyQueue();
                } else if (notification.type === 'live_session_seeker_confirmed_ready') {
                    log('clergy', `🔔 Seeker ID ${notification.request_id} has confirmed readiness. Start the video call!`);
                    fetchClergyQueue();
                }
            }
        }

        // ================= SEEKER API ACTIONS =================

        async function joinQueue() {
            if (!state.seeker.token) {
                alert('Seeker is not authenticated. Please log in.');
                return;
            }
            
            const clergyId = document.getElementById('seeker-clergy-select').value;
            const message = document.getElementById('seeker-queue-message').value;
            
            if (!clergyId) {
                alert('Please select a Clergy to join their queue.');
                return;
            }

            log('seeker', `Calling POST /api/seeker/live-session-queue/join (clergy_id: ${clergyId})...`);
            try {
                const response = await fetch('/api/seeker/live-session-queue/join', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.seeker.token}`
                    },
                    body: JSON.stringify({ clergy_id: clergyId, message: message })
                });
                const result = await response.json();
                
                if (result.status) {
                    const req = result.data.live_session_request;
                    state.seeker.activeRequest = req;
                    
                    log('seeker', `Successfully joined queue. Request ID: ${req.id}, Position: ${result.data.queue_position ?? 'Ready'}`);
                    updateSeekerQueueCard(req, result.data.queue_position);
                    
                    // Start Heartbeats
                    startHeartbeatTimer(req.id, result.data.heartbeat_interval_seconds || 40);
                } else {
                    log('seeker', `Failed to join queue: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('seeker', `Error joining queue: ${error.message}`, null, true);
            }
        }

        async function leaveQueue() {
            if (!state.seeker.activeRequest || !state.seeker.token) return;
            const id = state.seeker.activeRequest.id;
            
            log('seeker', `Calling POST /api/seeker/live-session-queue/${id}/leave...`);
            try {
                const response = await fetch(`/api/seeker/live-session-queue/${id}/leave`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.seeker.token}`
                    }
                });
                const result = await response.json();
                if (result.status) {
                    log('seeker', 'Successfully left the queue.');
                    resetSeekerState();
                } else {
                    log('seeker', `Leave failed: ${result.message}`, null, true);
                }
            } catch (error) {
                log('seeker', `Leave Error: ${error.message}`, null, true);
            }
        }

        async function confirmReady() {
            if (!state.seeker.activeRequest || !state.seeker.token) return;
            const id = state.seeker.activeRequest.id;

            log('seeker', `Calling POST /api/seeker/live-session-queue/${id}/confirm-ready...`);
            try {
                const response = await fetch(`/api/seeker/live-session-queue/${id}/confirm-ready`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.seeker.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    log('seeker', 'Confirmed ready successfully! Waiting for clergy to start the call.');
                    state.seeker.activeRequest = result.data.live_session_request;
                    updateSeekerQueueCard(state.seeker.activeRequest, null);
                } else {
                    log('seeker', `Confirm ready failed: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('seeker', `Confirm Ready Error: ${error.message}`, null, true);
            }
        }

        async function refreshSeekerStatus() {
            if (!state.seeker.activeRequest || !state.seeker.token) return;
            const id = state.seeker.activeRequest.id;

            log('seeker', `Calling GET /api/seeker/live-session-requests/${id}...`);
            try {
                const response = await fetch(`/api/seeker/live-session-requests/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.seeker.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    state.seeker.activeRequest = result.data;
                    log('seeker', `Fetched status: status="${result.data.status}", video_status="${result.data.video_status}"`);
                    updateSeekerQueueCard(result.data, result.data.queue_position);
                    
                    // If video started but seeker hasn't joined Agora yet
                    if (result.data.video_status === 'started' && !state.seeker.agora.isJoined) {
                        log('seeker', 'Detecting started call. Querying Agora credentials to join call...');
                        queryAgoraJoinCredentials('seeker', id);
                    }
                    
                    // Handle cancelled status
                    if (['cancelled', 'completed', 'abandoned', 'confirm_timeout'].includes(result.data.status)) {
                        log('seeker', `Request terminated with status "${result.data.status}". Resetting seeker view.`);
                        resetSeekerState();
                    }
                } else {
                    log('seeker', 'Request not found on API (it may have been completed/cancelled/deleted). State reset.', null, true);
                    resetSeekerState();
                }
            } catch (error) {
                log('seeker', `Refresh Error: ${error.message}`, null, true);
            }
        }

        async function sendSeekerHeartbeat() {
            if (!state.seeker.activeRequest || !state.seeker.token) return;
            const id = state.seeker.activeRequest.id;

            const pulseDot = document.getElementById('seeker-heartbeat-dot');
            pulseDot.className = 'w-2 h-2 rounded-full bg-yellow-500 animate-pulse';

            try {
                const response = await fetch(`/api/seeker/live-session-queue/${id}/heartbeat`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.seeker.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    pulseDot.className = 'w-2 h-2 rounded-full bg-emerald-500';
                    document.getElementById('seeker-heartbeat-text').innerText = 'Healthy';
                    state.seeker.heartbeatFailCount = 0;
                    log('seeker', '💓 Heartbeat ping success.');
                } else {
                    pulseDot.className = 'w-2 h-2 rounded-full bg-rose-500';
                    document.getElementById('seeker-heartbeat-text').innerText = 'Rejected';
                    log('seeker', `💓 Heartbeat rejected by API: ${result.message}`, null, true);
                    handleHeartbeatFailure();
                }
            } catch (error) {
                pulseDot.className = 'w-2 h-2 rounded-full bg-rose-500';
                document.getElementById('seeker-heartbeat-text').innerText = 'Offline';
                log('seeker', `💓 Heartbeat communication error: ${error.message}`, null, true);
                handleHeartbeatFailure();
            }
        }

        function handleHeartbeatFailure() {
            state.seeker.heartbeatFailCount++;
            if (state.seeker.heartbeatFailCount >= 3) {
                log('seeker', '🚨 Heartbeat failed 3 times consecutive. Stopping pinger.', null, true);
                clearInterval(state.seeker.heartbeatInterval);
                state.seeker.heartbeatInterval = null;
            }
        }

        function startHeartbeatTimer(reqId, intervalSeconds) {
            if (state.seeker.heartbeatInterval) clearInterval(state.seeker.heartbeatInterval);
            
            document.getElementById('seeker-heartbeat-btn').classList.remove('hidden');
            document.getElementById('seeker-heartbeat-dot').className = 'w-2 h-2 rounded-full bg-emerald-500';
            document.getElementById('seeker-heartbeat-text').innerText = 'Active';
            
            log('seeker', `Heartbeat pinger started (every ${intervalSeconds} seconds).`);
            
            // Trigger immediately
            sendSeekerHeartbeat();
            
            state.seeker.heartbeatInterval = setInterval(sendSeekerHeartbeat, intervalSeconds * 1000);
        }

        function resetSeekerState() {
            if (state.seeker.heartbeatInterval) {
                clearInterval(state.seeker.heartbeatInterval);
                state.seeker.heartbeatInterval = null;
            }
            state.seeker.activeRequest = null;
            state.seeker.heartbeatFailCount = 0;
            
            document.getElementById('seeker-heartbeat-btn').classList.add('hidden');
            document.getElementById('seeker-heartbeat-dot').className = 'w-2 h-2 rounded-full bg-slate-600';
            document.getElementById('seeker-heartbeat-text').innerText = 'Idle';
            
            document.getElementById('seeker-queue-card').classList.add('hidden');
            document.getElementById('seeker-join-btn').classList.remove('hidden');
            document.getElementById('seeker-leave-btn').classList.add('hidden');
            
            // Hangup call
            state.seeker.agora.leave();
        }

        function updateSeekerQueueCard(req, position) {
            const card = document.getElementById('seeker-queue-card');
            const title = document.getElementById('seeker-queue-title');
            const desc = document.getElementById('seeker-queue-desc');
            const confirmBtn = document.getElementById('seeker-confirm-ready-container');
            const joinBtn = document.getElementById('seeker-join-btn');
            const leaveBtn = document.getElementById('seeker-leave-btn');

            card.classList.remove('hidden');
            joinBtn.classList.add('hidden');
            leaveBtn.classList.remove('hidden');

            // Apply card colors based on status
            if (req.status === 'queued') {
                card.className = 'p-4 rounded-xl border border-blue-900/30 bg-blue-950/20';
                title.innerText = `Queued (Position #${position ?? '?'})`;
                title.className = 'font-bold text-sm text-blue-300';
                desc.innerText = `You are waiting in line. Send heartbeats to stay active.`;
                confirmBtn.classList.add('hidden');
            } else if (req.status === 'awaiting_confirm') {
                card.className = 'p-4 rounded-xl border border-amber-900/30 bg-amber-950/20';
                title.innerText = 'Action Required';
                title.className = 'font-bold text-sm text-amber-300';
                desc.innerText = 'It is your turn. Please confirm you are ready.';
                confirmBtn.classList.remove('hidden');
            } else if (req.status === 'ready') {
                card.className = 'p-4 rounded-xl border border-cyan-900/30 bg-cyan-950/20';
                title.innerText = 'Confirmed Ready';
                title.className = 'font-bold text-sm text-cyan-300';
                desc.innerText = 'Waiting room active. The clergy has been notified and will start the video shortly.';
                confirmBtn.classList.add('hidden');
            } else if (req.status === 'active') {
                card.className = 'p-4 rounded-xl border border-emerald-900/30 bg-emerald-950/20';
                title.innerText = 'Session Active';
                title.className = 'font-bold text-sm text-emerald-300';
                desc.innerText = 'Video call is in progress.';
                confirmBtn.classList.add('hidden');
            } else {
                card.className = 'p-4 rounded-xl border border-slate-800 bg-slate-900/40';
                title.innerText = `Status: ${req.status}`;
                title.className = 'font-bold text-sm text-slate-300';
                desc.innerText = `Video status: ${req.video_status}`;
                confirmBtn.classList.add('hidden');
            }
        }

        // ================= CLERGY API ACTIONS =================

        async function toggleClergyAvailability() {
            if (!state.clergy.token) return;
            const newStatus = !state.clergy.isAvailable;
            
            log('clergy', `Calling POST /api/clergy/live-availability (is_available_for_live: ${newStatus})...`);
            try {
                const response = await fetch('/api/clergy/live-availability', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    },
                    body: JSON.stringify({
                        is_available_for_live: newStatus,
                        live_unavailable_message: newStatus ? null : "Away from keyboard"
                    })
                });
                const result = await response.json();
                
                if (result.status) {
                    state.clergy.isAvailable = !!result.data.is_available_for_live;
                    updateClergyAvailUI();
                    log('clergy', `Live availability updated successfully to: ${state.clergy.isAvailable}`);
                } else {
                    log('clergy', `Failed to toggle availability: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Error toggling availability: ${error.message}`, null, true);
            }
        }

        function updateClergyAvailUI() {
            const btn = document.getElementById('clergy-avail-toggle');
            if (state.clergy.isAvailable) {
                btn.className = "px-2 py-0.5 bg-emerald-950 text-emerald-400 border border-emerald-900/30 hover:bg-emerald-900/40 rounded text-[10px] font-bold";
                btn.innerHTML = `<i class="fa-solid fa-circle-dot mr-1 text-emerald-400"></i> Online`;
            } else {
                btn.className = "px-2 py-0.5 bg-rose-950 text-rose-400 border border-rose-900/30 hover:bg-rose-900/40 rounded text-[10px] font-bold";
                btn.innerHTML = `<i class="fa-solid fa-circle-dot mr-1 text-rose-500"></i> Offline`;
            }
        }

        async function fetchClergyQueue() {
            if (!state.clergy.token) return;
            
            // Trigger API Call
            try {
                const response = await fetch('/api/clergy/live-session-requests?tab=queue', {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    // Update active request
                    const snapshot = result.data;
                    state.clergy.activeRequest = snapshot.current_session;
                    updateClergyActiveCard(snapshot.current_session);
                    
                    // Update Queue list UI
                    updateClergyQueueListUI(snapshot.queue);
                }
            } catch (error) {
                log('clergy', `Error polling queue: ${error.message}`, null, true);
            }
        }

        function updateClergyActiveCard(session) {
            const card = document.getElementById('clergy-active-card');
            const desc = document.getElementById('clergy-active-desc');
            const statusBadge = document.getElementById('clergy-active-status');
            const details = document.getElementById('clergy-active-details');
            
            const startBtn = document.getElementById('clergy-start-btn');
            const completeBtn = document.getElementById('clergy-complete-btn');

            if (!session) {
                card.className = 'p-4 bg-slate-900/40 rounded-xl border border-slate-800/80 space-y-3';
                desc.innerText = 'No active seeker in waiting room.';
                statusBadge.innerText = 'IDLE';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700';
                details.classList.add('hidden');
                
                startBtn.classList.add('hidden');
                completeBtn.classList.add('hidden');
                document.getElementById('clergy-reject-box').classList.add('hidden');
                return;
            }

            details.classList.remove('hidden');
            document.getElementById('clergy-reject-box').classList.remove('hidden');
            document.getElementById('clergy-active-seeker-name').innerText = session.seeker ? session.seeker.name : 'Unknown';
            document.getElementById('clergy-active-seeker-email').innerText = session.seeker ? session.seeker.email : 'Unknown';
            document.getElementById('clergy-active-message').innerText = session.message || 'No message provided';

            // Apply style based on status
            statusBadge.innerText = session.status.toUpperCase();
            
            if (session.status === 'awaiting_confirm') {
                card.className = 'p-4 bg-amber-950/10 border border-amber-900/30 rounded-xl space-y-3';
                desc.innerText = 'Seeker has been notified. Waiting for Seeker to click Ready.';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-amber-900/10 text-amber-400 border border-amber-500/20';
                
                startBtn.classList.add('hidden');
                completeBtn.classList.add('hidden');
            } else if (session.status === 'ready') {
                card.className = 'p-4 bg-cyan-950/10 border border-cyan-900/30 rounded-xl space-y-3';
                desc.innerText = 'Seeker is ready! Start the call now.';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-cyan-900/10 text-cyan-400 border border-cyan-500/20';
                
                startBtn.classList.remove('hidden');
                completeBtn.classList.add('hidden');
            } else if (session.status === 'active') {
                card.className = 'p-4 bg-emerald-950/10 border border-emerald-900/30 rounded-xl space-y-3';
                desc.innerText = 'Live video session active. Click Complete when finished.';
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-900/10 text-emerald-400 border border-emerald-500/20';
                
                startBtn.classList.add('hidden');
                completeBtn.classList.remove('hidden');

                // If clergy hasn't joined Agora yet
                if (session.video_status === 'started' && !state.clergy.agora.isJoined) {
                    log('clergy', 'Detecting active video call. Querying credentials to join...');
                    queryAgoraJoinCredentials('clergy', session.id);
                }
            } else {
                card.className = 'p-4 bg-slate-900/40 rounded-xl border border-slate-800/80 space-y-3';
                desc.innerText = `Session status is "${session.status}".`;
                statusBadge.className = 'text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-slate-400 border border-slate-700';
                
                startBtn.classList.add('hidden');
                completeBtn.classList.add('hidden');
            }
        }

        function updateClergyQueueListUI(queue) {
            const listEl = document.getElementById('clergy-queue-items');
            const countEl = document.getElementById('clergy-queue-count');

            countEl.innerText = queue.length;
            if (queue.length === 0) {
                listEl.innerHTML = `<div class="text-center text-xs text-slate-600 py-2.5 border border-dashed border-slate-800 rounded-lg">Queue is empty</div>`;
                return;
            }

            listEl.innerHTML = '';
            queue.forEach(item => {
                const seekerName = item.seeker ? item.seeker.name : `User ID ${item.seeker_id}`;
                const detail = `<div class="flex items-center justify-between p-2 bg-slate-900/60 rounded-lg border border-slate-800 text-xs">
                    <div>
                        <span class="font-semibold text-slate-300">#${item.queue_position}. ${seekerName}</span>
                        <span class="block text-[10px] text-slate-500">Msg: "${item.message || 'none'}"</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[9px] font-bold uppercase tracking-wider text-blue-400 bg-blue-900/10 px-1.5 py-0.5 rounded border border-blue-900/20">${item.status}</span>
                        <button onclick="removeQueueItem(${item.id}, '${seekerName}')" class="text-rose-400 hover:text-rose-300 p-1" title="Remove Seeker">
                            <i class="fa-solid fa-trash-can text-[10px]"></i>
                        </button>
                    </div>
                </div>`;
                listEl.innerHTML += detail;
            });
        }

        async function notifyNextSeeker() {
            if (!state.clergy.token) return;
            
            log('clergy', 'Calling POST /api/clergy/live-session-queue/notify-next...');
            try {
                const response = await fetch('/api/clergy/live-session-queue/notify-next', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    log('clergy', `Notify Next Success: ${result.message}`, result.data);
                    fetchClergyQueue();
                } else {
                    log('clergy', `Notify Next failed: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Notify Next error: ${error.message}`, null, true);
            }
        }

        async function startVideoCall() {
            if (!state.clergy.activeRequest || !state.clergy.token) return;
            const id = state.clergy.activeRequest.id;

            log('clergy', `Calling POST /api/clergy/live-session-requests/${id}/video/start...`);
            try {
                const response = await fetch(`/api/clergy/live-session-requests/${id}/video/start`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    log('clergy', 'Video started successfully! Connecting to Agora RTC...');
                    
                    const agora = result.data.agora || null;
                    if (agora) {
                        joinAgoraCall('clergy', agora.channel, agora.token, agora.uid);
                    } else {
                        log('clergy', 'Warning: Agora object missing from API response. Trying fallback query...', null, true);
                        queryAgoraJoinCredentials('clergy', id);
                    }
                    
                    fetchClergyQueue();
                } else {
                    log('clergy', `Start Video failed: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Start Video error: ${error.message}`, null, true);
            }
        }

        async function completeVideoCall() {
            if (!state.clergy.activeRequest || !state.clergy.token) return;
            const id = state.clergy.activeRequest.id;

            log('clergy', `Calling POST /api/clergy/live-session-requests/${id}/video/complete...`);
            try {
                const response = await fetch(`/api/clergy/live-session-requests/${id}/video/complete`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    }
                });
                const result = await response.json();
                
                if (result.status) {
                    log('clergy', 'Video call completed successfully! Disconnecting from Agora...');
                    state.clergy.agora.leave();
                    
                    // Refresh queue automatically
                    fetchClergyQueue();
                } else {
                    log('clergy', `Complete Video failed: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Complete Video error: ${error.message}`, null, true);
            }
        }

        async function rejectActiveSession() {
            if (!state.clergy.activeRequest || !state.clergy.token) return;
            const id = state.clergy.activeRequest.id;
            const reason = document.getElementById('clergy-reject-reason').value || "Session cancelled by clergy.";

            log('clergy', `Rejecting active session ID ${id} with reason: "${reason}"...`);
            try {
                const response = await fetch(`/api/clergy/live-session-requests/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    },
                    body: JSON.stringify({ reason: reason })
                });
                const result = await response.json();
                
                if (result.status) {
                    log('clergy', `Successfully rejected active session ID ${id}.`);
                    document.getElementById('clergy-reject-reason').value = '';
                    fetchClergyQueue();
                } else {
                    log('clergy', `Failed to reject session: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Reject session error: ${error.message}`, null, true);
            }
        }

        async function removeQueueItem(id, seekerName) {
            if (!state.clergy.token) return;
            
            const reason = prompt(`Enter reason to remove ${seekerName} from the queue:`, "Scheduling conflict / No response");
            if (reason === null) return; // user cancelled prompt

            log('clergy', `Removing queue item ID ${id} (Seeker: ${seekerName}) with reason: "${reason}"...`);
            try {
                const response = await fetch(`/api/clergy/live-session-requests/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${state.clergy.token}`
                    },
                    body: JSON.stringify({ reason: reason })
                });
                const result = await response.json();
                
                if (result.status) {
                    log('clergy', `Successfully removed ${seekerName} from the queue.`);
                    fetchClergyQueue();
                } else {
                    log('clergy', `Failed to remove seeker: ${result.message}`, null, true);
                    alert(result.message);
                }
            } catch (error) {
                log('clergy', `Remove queue item error: ${error.message}`, null, true);
            }
        }

        // ================= AGORA UTILITY FUNCTIONS =================

        async function queryAgoraJoinCredentials(role, requestId) {
            const token = state[role].token;
            if (!token) return;

            log(role, `Querying Agora join credentials for Request ID ${requestId}...`);
            try {
                const response = await fetch(`/api/live-session-requests/${requestId}/agora/join`, {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${token}`
                    }
                });
                const result = await response.json();
                
                if (result.status && result.data.agora) {
                    const agora = result.data.agora;
                    joinAgoraCall(role, agora.channel, agora.token, agora.uid);
                } else {
                    log(role, `Failed to query credentials: ${result.message}`, null, true);
                    document.getElementById(`${role}-agora-btn`).classList.remove('hidden');
                }
            } catch (error) {
                log(role, `Query credentials error: ${error.message}`, null, true);
                document.getElementById(`${role}-agora-btn`).classList.remove('hidden');
            }
        }

        function joinAgoraCall(role, channel, token, uid) {
            if (!AGORA_APP_ID) {
                log(role, `Simulation Mode: AGORA_APP_ID is missing. Simulating video connection for channel "${channel}"...`, null, false);
                simulateAgoraVideo(role);
                return;
            }
            
            state[role].agora.join(AGORA_APP_ID, channel, token, uid);
        }

        function hangupAgora(role) {
            state[role].agora.leave();
        }

        function manualJoinAgora(role) {
            const activeReq = state[role].activeRequest;
            if (!activeReq) {
                alert('No active request loaded for ' + role);
                return;
            }
            queryAgoraJoinCredentials(role, activeReq.id);
        }

        // Simulator Fallback in case Agora App ID is not set
        function simulateAgoraVideo(role) {
            const local = document.getElementById(`${role}-local`);
            const remote = document.getElementById(`${role}-remote`);

            local.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-emerald-400 bg-slate-900 p-4 text-center">
                <i class="fa-solid fa-circle-user text-3xl mb-1.5"></i>
                <span class="font-bold text-xs">Local Simulated Video</span>
                <span class="text-[9px] text-slate-500">Camera active (simulated)</span>
            </div>`;

            remote.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-indigo-400 bg-slate-900 p-4 text-center">
                <i class="fa-solid fa-circle-user text-3xl mb-1.5"></i>
                <span class="font-bold text-xs">Remote Participant</span>
                <span class="text-[9px] text-slate-500">Connected (simulated)</span>
            </div>`;

            document.getElementById(`${role}-agora-btn`).classList.add('hidden');
            document.getElementById(`${role}-hangup-btn`).classList.remove('hidden');
            
            log(role, 'Agora Call connected in Simulation Mode.');
        }
    </script>
</body>
</html>
