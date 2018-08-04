<?php
/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 */
/* @var $factory Factory */
/* @var $session Session */
/* @var $encryption Encryption */
/* @var $sessionUID int */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp int */
/* @var $searchTerm string */
/* @var $host string */
/* @var $userAgent string */
if (!defined('PASSTOOL')) {
    die();
}


if ($session->isAuthenticated() === true) {
    $factory->redirect('account');
}
?>



<div id="login-form" class="form">
    <input type="email" id="username-input" placeholder="Benutzername">
    <input type="email" class="honeypot-field" id="honeypot" value="">
    <input type="password" id="password-input">
    <a id="login-button" class="button">Login</a>
    <a class="forgot-password-link" href="/newpassword">Passwort vergessen</a>
</div>
<script>
    $('#login-button').bind('click touch', function () {

        if ($('.ajax-message').hasClass('login-failed')) {
            $('.ajax-message').removeClass('login-failed');
            $('.ajax-message').text('');
        }

        var request = {};
        request.username = document.getElementById('username-input').value;
        request.honeypot = document.getElementById('honeypot').value;
        request.password = document.getElementById('password-input').value;
        request.action = 'Login';
        request.timestamp = <?php echo microtime(true); ?>;
        request.tk = token;
        request.ts = timestamp;
        request.ipaddress = ipaddress;
        request.host = host;
        request.userAgent = userAgent;

        $.ajax({
            'type': 'POST',
            'data': {"request": JSON.stringify(request)},
            'url': getAjaxUrl(),
            'success': function (data) {
                if (parseInt(data) == 1) {
                    window.location.href = '/account';
                } else {
                    $('.ajax-message').addClass('login-failed');
                    $('.ajax-message').text(data);
                    setTimeout(function () {
                        if ($('.ajax-message').hasClass('login-failed')) {
                            $('.ajax-message').removeClass('login-failed');
                            $('.ajax-message').text('');
                        }
                    }, 2000);
                }
            },
            error: function (jqXHR, exception) {
                $('.ajax-message').text(jqXHR + ' ' + exception);
            }
        });
    });
</script>
<div class="loading-div">

</div>
<div class="ajax-message">

</div>
