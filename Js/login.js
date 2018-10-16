/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */

$(document).ready(function () {
    $('#login-button').bind('click touch', function () {

        if ($('.ajax-message').hasClass('login-failed')) {
            $('.ajax-message').removeClass('login-failed');
            $('.ajax-message').text('');
        }

        var request = {};
        request.username = document.getElementById('username-input').value;
        request.honeypot = document.getElementById('honeypot').value;
        request.password = document.getElementById('password-input').value;
        request.action = 'Login';
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/login';
                } else {
                    $('.ajax-message').addClass('alert-error');
                    $('.ajax-message').text(data);
                    setTimeout(function () {
                        if ($('.ajax-message').hasClass('alert-error')) {
                            $('.ajax-message').removeClass('alert-error');
                            $('.ajax-message').text('');
                            $('.ajax-message').hide();
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




