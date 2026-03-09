import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
// let channelName = import.meta.env.VITE_REVERB_CHANNEL_NAME;
// let channelName = 'chat.2';

// window.Echo.private(channelName)
//     .listen('MessageEvent', (e) => {
//         console.log(e);
//     });
// let userId = 1;
// window.Echo.private('App.Models.User.' + userId)
//     .notification((notification) => {
//         console.log("New Notification:", notification);
//     });

