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
/* @var $account Account */
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

$debugger = $factory->getDebugger();

if ($session->isAuthenticated() !== true) {
    $factory->redirect('login');
}

if ($session->needAuthenticator() !== false) {
    $factory->redirect('authenticator');
}

if ($account->needPasswordChange($sessionUID) === true) {
    $factory->redirect('updatepassword');
}


try {
    define("TOKEN", "secret-token");                                       // The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
    define("REMOTE_REPOSITORY", "git@bitbucket.org:LXkennstenich/password-tool.git"); // The SSH URL to your repository
    define("DIR", ROOT_DIR);                          // The path to your repostiroy; this must begin with a forward slash (/)
    define("BRANCH", "origin/master");                                 // The branch route
    define("LOGFILE", "update.log");                                       // The name of the file you want to log to.
    define("GIT", "/usr/local/git/bin/git");                                         // The path to the git executable
    define("AFTER_PULL", "");

    $file = fopen(LOGFILE, "a");
    $time = time();
    $token = "secret-token";

// retrieve the token
    /*
      if (!$token && isset($_SERVER["HTTP_X_HUB_SIGNATURE"])) {
      list($algo, $token) = explode("=", $_SERVER["HTTP_X_HUB_SIGNATURE"], 2) + array("", "");
      } elseif (isset($_SERVER["HTTP_X_GITLAB_TOKEN"])) {
      $token = $_SERVER["HTTP_X_GITLAB_TOKEN"];
      } elseif (isset($_GET["token"])) {
      $token = $_GET["token"];
      }
     */
// log the time
    date_default_timezone_set("UTC");
    fputs($file, date("d-m-Y (H:i:s)", $time) . "\n");

// function to forbid access
    function forbid($file, $reason) {
        // explain why
        if ($reason)
            fputs($file, "=== ERROR: " . $reason . " ===\n");
        fputs($file, "*** ACCESS DENIED ***" . "\n\n\n");
        fclose($file);

        // forbid
        header("HTTP/1.0 403 Forbidden");
        exit;
    }

// function to return OK
    function ok() {
        ob_start();
        header("HTTP/1.1 200 OK");
        header("Connection: close");
        header("Content-Length: " . ob_get_length());
        ob_end_flush();
        ob_flush();
        flush();
    }

    // ensure directory is a repository
    if (file_exists(DIR) && is_dir(DIR)) {
        try {
            // pull
            fputs($file, "*** AUTO PULL INITIATED ***" . "\n");
            chdir(DIR);
            $result = shell_exec(GIT . " pull 2>&1");

            fputs($file, $result . "\n");

            // return OK to prevent timeouts on AFTER_PULL
            ok();

            // execute AFTER_PULL if specified
            if (!empty(AFTER_PULL)) {
                try {
                    fputs($file, "*** AFTER_PULL INITIATED ***" . "\n");
                    $result = shell_exec(AFTER_PULL);
                    fputs($file, $result . "\n");
                } catch (Exception $e) {
                    fputs($file, $e . "\n");
                }
            }
            fputs($file, "*** AUTO PULL COMPLETE ***" . "\n");
        } catch (Exception $e) {
            fputs($file, $e . "\n");
        }
    } else {
        fputs($file, "=== ERROR: DIR is not a repository ===" . "\n");
    }



    fputs($file, "\n\n" . PHP_EOL);
    fclose($file);
} catch (Exception $ex) {
    $debugger->log($ex->getMessage());
}

