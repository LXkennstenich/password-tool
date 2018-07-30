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
    $('#createNewDatasetButton').fancybox();

    $('#createDatasetButton').bind('click touch', function () {
        var request = {};
        request.action = 'NewDataset';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.title = document.getElementById('datasetTitle').value;
        request.login = document.getElementById('datasetLogin').value;
        request.password = document.getElementById('datasetPassword').value;
        request.url = document.getElementById('datasetURL').value;
        request.project = document.getElementById('datasetProject').value;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
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

    $('#generatePasswordButton').bind('click touch', function () {
        var request = {};
        request.action = 'GeneratePassword';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.length = document.getElementById('passwordLengthBox').value;
        request.lowerCharacters = document.getElementById('lowerCharacters').checked !== false ? 1 : 0;
        request.highCharacters = document.getElementById('highCharacters').checked !== false ? 1 : 0;
        request.numbers = document.getElementById('numbers').checked !== false ? 1 : 0;
        request.specialChars = document.getElementById('specialchars').checked !== false ? 1 : 0;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                $('#datasetPassword').val(data);
                $('#datasetPassword').text(data);
            },
            error: function (jqXHR, exception) {

            }

        });
    });

    $('#validate-auth-setup-button').bind('click touch', function () {
        var request = {};
        request.action = 'AuthSetup';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.validate = 1;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/authenticator';
                }
            },
            error: function (jqXHR, exception) {

            }

        });
    });

    $('#authenticator-button').bind('click touch', function () {
        var request = {};
        request.action = 'ValidateAuth';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.code = document.getElementById('authenticator-code').value;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').text("Authenticator Code ist nicht gültig!");
                }
            },
            error: function (jqXHR, exception) {

            }

        });
    });

    $('#update-password-button').bind('click touch', function () {
        var request = {};
        request.action = 'UpdatePassword';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.username = document.getElementById('username-input').value;
        request.oldPassword = document.getElementById('password-input-old').value;
        request.newPassword = document.getElementById('password-input-new').value;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').text("Benutzername oder Passwort sind nicht korrekt");
                }
            },
            error: function (jqXHR, exception) {

            }

        });
    });

   

});


function copy(encrypted) {
    var request = {};
    request.action = 'DecryptPassword';
    request.file = 'dataset';
    request.tk = token;
    request.ts = timestamp;
    request.ipaddress = ipaddress;
    request.uid = uid;
    request.searchTerm = '';
    request.encrypted = encrypted;
    $.ajax({
        'type': 'POST',
        'data': {"request": JSON.stringify(request)},
        'url': getAjaxUrl(),
        'success': function (data) {
            var $temp = $("<input>");
            $("body").append($temp);
            var value = data;
            var $pass = $('<input id="realPass" value="' + value + '">');
            $("body").append($pass);
            $temp.val(document.getElementById('realPass').value).select();
            document.execCommand("copy");
            $temp.remove();
            /*
             var copynotice = $(this).next('#copy-notice').text('Kopiert!');
             $(copynotice).text('Kopiert!');
             var interval = setInterval(function () {
             $(copynotice).text('');
             clearInterval(interval);
             }, 1000);*/

        },
        error: function (jqXHR, exception) {
            $(selector).siblings('p').text(jqXHR + exception);
        }

    });

 

}

function showPassword(selector) {

    if ($(selector).hasClass('show-password')) {
        var hiddenValue = $(selector).parent('.row').parent('.content').siblings('.datasetHidden').val();
        $(selector).removeClass('show-password');
        $(selector).siblings('p').text(hiddenValue);
    } else {
        var request = {};
        request.action = 'DecryptPassword';
        request.file = 'dataset';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.searchTerm = '';
        request.encrypted = $(selector).parent('.row').parent('.content').next('.datasetEncrypted').val();

        var hiddenValue = $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                $(selector).addClass('show-password');
                $(selector).siblings('p').text(data);
            },
            error: function (jqXHR, exception) {
                $(selector).siblings('p').text(jqXHR + exception);
            }

        });
    }
}


function updatePasswordBox(val) {
    $('#passwordLengthBox').val(val);
}

function updateRange(val) {
    $('#datasetPasswordLength').val(val);
}

