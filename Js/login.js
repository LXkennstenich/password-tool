/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */

var $loading = $('#loading-div').hide();
var $loginmessage = $('#login-message').hide();
var $registermessage = $('#register-message').hide();
var $ajaxMessage = $('#ajax-message').hide();
$(document)
        .ajaxStart(function () {
            $loading.show();
        })
        .ajaxStop(function () {
            $loading.hide();
            $loginmessage.show();
            $registermessage.show();
            $ajaxMessage.show();
        });

$(document).ready(function () {
    function getAjaxUrl() {
        var hostname = document.location.hostname;
        var url = "https://" + hostname + "/" + "Ajax.php";
        return url;
    }

    $('#login-button').bind('click touch', function () {
        var request = {};
        request.username = document.getElementById('username-input').value;
        request.password = document.getElementById('password-input').value;
        request.action = 'Login';
        request.tk = '';
        request.ts = '';
        request.ipaddress = document.getElementById('session-ipaddress').value;
        
        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if(parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message .text').addClass('error');
                displayNotice(jqXHR + exception);
            }

        });
    });

});



