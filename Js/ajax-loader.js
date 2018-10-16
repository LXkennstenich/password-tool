 var $loading = $('.loading-div img').hide();
    var $ajaxMessage = $('.ajax-message').hide();
    $(document)
            .ajaxStart(function () {
                $loading.show();
            })
            .ajaxStop(function () {
                $loading.hide();
                $ajaxMessage.show();
            });