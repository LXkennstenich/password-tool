/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
$(document).ready(function () {
    $('#createNewDatasetButton').fancybox();
    $('#newUserLink').fancybox();

    $('.container').hide();

    $('#navbar-trigger').bind('click touch', function () {
        if ($(this).hasClass('open') === false) {
            $(this).addClass('open');
            $(this).next('.container').slideDown('slow');
        } else {
            $(this).removeClass('open');
            $(this).next('.container').slideUp('slow');
        }
    });

    $('#clearCacheButton').bind('click touch', function () {
        var request = {};
        request.action = 'ClearCache';
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
                if (parseInt(data) != 1) {
                    alert('Cache konnte nicht geleert werden!');
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message .text').addClass('error');
                displayNotice(jqXHR + exception);
            }

        });
    });

    $('#createDatasetButton').bind('click touch', function () {
        var request = {};
        request.action = 'NewDataset';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.title = document.getElementById('datasetTitle').value;
        request.login = document.getElementById('datasetLogin').value;
        request.password = document.getElementById('datasetPassword').value;
        request.url = document.getElementById('datasetURL').value;
        request.project = document.getElementById('datasetProject').value != '' ? document.getElementById('datasetProject').value : document.getElementById('project-select').value;

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

    $('.project-list-link').bind('click touch', function () {
        $(this).submit();
    });

    $('#updateSystemSettingsButton').bind('click touch', function () {
        var request = {};
        request.action = 'UpdateSystemSettings';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.cronActive = document.getElementById('cronActive').checked !== false ? 1 : 0;
        request.clearSessionData = document.getElementById('cronClearSessionData').checked !== false ? 1 : 0;
        request.recrypt = document.getElementById('cronRecrypt').checked !== false ? 1 : 0;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/settings';
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
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
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
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
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
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.code = document.getElementById('authenticator-code').value;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').text(data);
                }
            },
            error: function (jqXHR, exception) {

            }

        });
    });

    $('#update-password-button').bind('click touch', function () {
        var request = {};
        request.action = 'UpdatePassword';
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
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

    $('#input-search').keypress(function (e) {
        if (e.which == 13) {
            $('#input-search').submit();
            return false;
        }
    });

    $('#newUserButton').bind('click touch', function () {
        var request = {};
        request.action = 'NewUser';
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.username = document.getElementById('newUsername').value;
        request.accessLevel = document.getElementById('newAccessLevel').value;
        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').text(data);
                }
            }
        });
    });

});


function showPassword(selector) {

    if ($(selector).hasClass('show-password')) {
        $(selector).removeClass('show-password');
        $(selector).removeClass('fa-eye-slash');
        var passwordElement = $(selector).siblings('p');
        var password = passwordElement.text();
        var passwordLength = password.length;

        var hidden = '';

        for (i = 0; i <= passwordLength; i++) {
            hidden += '*';
        }

        passwordElement.text(hidden);

    } else {
        var request = {};
        request.action = 'DecryptPassword';
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.id = $(selector).parent('.row').parent('.content').parent('.dataset').children('.datasetID').val();

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                $(selector).addClass('show-password');
                $(selector).addClass('fa-eye-slash');
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

