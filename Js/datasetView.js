/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */

/* @var $factory Factory */
/* @var $session Session */

$('.dataset-title').on("click", function () {
    if ($(this).hasClass('open') == false) {
        $(this).addClass('open');
        $(this).parent('h2').next('.content').slideDown('slow');
    } else {
        $(this).removeClass('open');
        $(this).parent('h2').next('.content').slideUp('slow');
    }
});



$('.delete-dataset-link').bind('click touch', function () {
    if (confirm("Den ausgewählten Datensatz wirklich löschen?")) {
        var request = {};
        request.action = 'DeleteDataset';
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.uid = uid;
        request.id = $(this).attr('datasetid');
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
                $('.ajax-message').text(jqXHR + exception);
            }

        });
    }
});
