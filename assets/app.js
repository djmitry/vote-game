/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import './styles/global.scss';

// start the Stimulus application
import './bootstrap';

require('bootstrap');

import { registerVueControllerComponents } from '@symfony/ux-vue';
registerVueControllerComponents(require.context('./vue/controllers', true, /\.vue$/));

let token = $('.mine').data('token');

function NotifServer() {
    let notif = new WebSocket("ws://localhost:6001");

    notif.onmessage = function (event) {
        console.log('onmessage');
        console.log(event.data);

        if (!event.data) {
            return;
        }

        let data = JSON.parse(event.data);

        console.log(data)

        if (data.score) {
            $('.mine .score').remove();
            $('.mine').append($('<div class="score">'+data.score+'</div>'));
        }
    }

    notif.onopen = function () {
        console.log('onopen');
        $('body').append('<p> >>> Connected</p>');
    }

    notif.onerror = function (error) {
        console.log('onerror');
        $('body').append('<p> >>> Error...</p>');
    }

    return notif;
}

let notif = NotifServer();

$('.mine').click(function () {
    console.log('mine', token);
    notif.send(JSON.stringify({token: token}));
});