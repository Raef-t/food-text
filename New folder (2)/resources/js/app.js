require('./bootstrap');
import './bootstrap';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ccbb64f2a0697075aaa5',
    cluster: 'sa1',
    encrypted: true,
    forceTLS: true
});
