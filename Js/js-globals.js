

var searchTerm = $('#searchTerm').val();
var uid = $('#sessionUID').val();
var token = $('#sessionToken').val();
var timestamp = $('#sessionTimestamp').val();
var ipaddress = $('#sessionIpAddress').val();
var host = $('#sessionHost').val();
var userAgent = $('#sessionUserAgent').val();
var accessLevel = $('#sessionAccessLevel').val();
var requestTimestamp = $('#requestTimestamp').val();

    function getAjaxUrl() {
        var hostname = document.location.hostname;
        var url = "https://" + hostname + "/" + "Ajax";
        return url;
    }

    $.ajaxSetup({
        async: true,
        cache: true,
        url: getAjaxUrl()
    });
