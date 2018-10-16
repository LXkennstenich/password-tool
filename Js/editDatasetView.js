/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */

$(document).ready(function () {
    $('#updateDatasetButton').on('click touch', function () {
        var request = {};
        request.action = 'UpdateDataset';
        request.timestamp = requestTimestamp;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;
        request.uid = uid;
        request.accessLevel = accessLevel;
        request.id = document.getElementById('edit-dataset-id').value;
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
                    window.location.href = "/account";
                } else {
                    $('.ajax-message').text(data);
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message').text(jqXHR + exception);
            }

        });
    });

    $('#regeneratePasswordButton').bind('click touch', function () {
        var request = {};
        request.action = 'GeneratePassword';
        request.timestamp = requestTimestamp;
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
});