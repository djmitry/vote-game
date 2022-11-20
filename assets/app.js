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

function NotifServer(){
    let notif = new WebSocket("ws://localhost:6001");

    notif.onmessage = function (event) {
        console.log('onmessage');
        console.log(event.data);
        $('.content').append('<p>'+ event.data +'</p>');
    }

    notif.onopen = function() {
        console.log('onopen');
        $('body').append('<p> >>> Connected</p>');
        var token_user = '123';
        var authElements = "{\"userData\":\""+token_user+"\"}";
        notif.send(authElements);
    }

    notif.onerror = function(error) {
        console.log('onerror');
        $('body').append('<p> >>> Error...</p>');
    }

    return notif;
}

let notif = NotifServer();

$('.mine').click(function() {
    console.log('mine');
    notif.send({some: 'some'});
});