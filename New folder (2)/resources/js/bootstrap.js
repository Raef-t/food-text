window._ = require('lodash');
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';




window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'ccbb64f2a0697075aaa5',
    cluster: 'sa1',
    encrypted: true
});
