/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
/* @var isSearch */
/* @var searchTerm */
/* @var uid */
/* @var token */
/* @var timestamp */
/* @var ipaddress */


$(document).ready(function () {

    $('#login-button').bind('click touch', function () {

        if ($('.ajax-message').hasClass('login-failed')) {
            $('.ajax-message').removeClass('login-failed');
            $('.ajax-message').text('');
        }

        var request = {};
        request.username = document.getElementById('username-input').value;
        request.password = document.getElementById('password-input').value;
        request.action = 'Login';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').addClass('login-failed');
                    $('.ajax-message').text('Benutzername oder Passwort ist falsch!');
                    setTimeout(function () {
                        if ($('.ajax-message').hasClass('login-failed')) {
                            $('.ajax-message').removeClass('login-failed');
                            $('.ajax-message').text('');
                        }
                    }, 2000);
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message').text(jqXHR + ' ' + exception);
            }
        });
    });
});



