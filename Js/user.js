var $loading = $('.loading-div').hide();
var $loginmessage = $('.login-message').hide();
var $ajaxMessage = $('.ajax-message').hide();

$(document)
        .ajaxStart(function () {
            $loading.show();
        })
        .ajaxStop(function () {
            $loading.hide();
            $loginmessage.show();
            $ajaxMessage.show();
        });
function getAjaxUrl() {
    var hostname = document.location.hostname;
    var url = "https://" + hostname + "/" + "Ajax.php";
    return url;
}

$(document).ready(function () {



    $('.nav1-link').fancybox();

    $('#createDatasetButton').bind('click touch', function () {
        var request = {};
        request.action = 'NewDataset';
        request.tk = document.getElementById('session-token').value;
        request.ts = document.getElementById('session-timestamp').value;
        request.ipaddress = document.getElementById('session-ipaddress').value;
        request.userID = document.getElementById('datasetUserID').value;
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

});

function copy(selector) {

}

function showPassword(selector) {
    if ($(selector).hasClass('show-password')) {
        var hiddenValue = $(selector).parent('.row').parent('.content').siblings('.datasetHidden').val();
        $(selector).removeClass('show-password');
        $(selector).siblings('p').text(hiddenValue);
    } else {
        var request = {};
        request.action = 'DecryptPassword';
        request.tk = document.getElementById('session-token').value;
        request.ts = document.getElementById('session-timestamp').value;
        request.ipaddress = document.getElementById('session-ipaddress').value;
        request.encrypted = $(selector).parent('.row').parent('.content').next('.datasetEncrypted').val();
        request.userID = $(selector).parent('.row').parent('.content').siblings('.datasetUserID').val();
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
