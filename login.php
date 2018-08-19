<?php
/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ------------------------------------------------- Verfügbare Objekte / Variablen ------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */

/* @var $factory Factory */
/* @var $session Session */
/* @var $system System */
/* @var $account Account */
/* @var $encryption Encryption */
/* @var $options Options */
/* @var $sessionUID string */
/* @var $sessionUsername string */
/* @var $sessionIP string */
/* @var $sessionToken string */
/* @var $sessionTimestamp string */
/* @var $sessionAccessLevel string */
/* @var $searchTerm string */
/* @var $isSearch string */
/* @var $host string */
/* @var $userAgent string */

/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
/* ---------------------------------------------------------------------------------------------------------------------------------- */
if (!defined('PASSTOOL')) {
    die();
}


if ($session->isAuthenticated() === true) {
    $factory->redirect('account');
}
?>

<div class="logo-container">
    <img src="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . '/Icons/logo-weese.png' ?>" width="300px">
</div>

<div id="login-form" class="form">
    <input type="email" class="honeypot-field" id="honeypot" value="">
    <input type="email" id="username-input" placeholder="Benutzername">
    <input type="password" id="password-input" placeholder="Passwort">
    <a id="login-button" class="button">Login</a>
    <a class="forgot-password-link" href="/newpassword">Passwort vergessen ?</a>
</div>
<?php include_once ELEMENTS_DIR . 'ajaxLoader.php'; ?>
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
        request.timestamp = requestTimestamp;
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

