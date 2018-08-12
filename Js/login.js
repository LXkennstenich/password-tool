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

    $('#requestNewPasswordButton').bind('click touch',function() {
        var request = {};
        request.action = 'NewPassword';
        request.tk = token;
        request.ts = timestamp;
        request.timestamp = requestTimestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.honeypot = document.getElementById('newPasswordHoneypot').value;
        request.username = document.getElementById('usernamePasswordReset').value;
        
        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/';
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message').text(jqXHR + ' ' + exception);
            }
        });
    });
});



