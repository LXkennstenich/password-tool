/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */

/* @var $factory Factory */
/* @var $session Session */

$(document).ready(function () {
    $('#updateDatasetButton').on('click touch', function () {
        var request = {};
        request.action = 'UpdateDataset';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
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
});