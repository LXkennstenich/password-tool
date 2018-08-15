<?php

if (!defined('PASSTOOL')) {
    die();
}

define("TOKEN", "secret-token");                                       // The secret token to add as a GitHub or GitLab secret, or otherwise as https://www.example.com/?token=secret-token
define("REMOTE_REPOSITORY", "git@bitbucket.org:LXkennstenich/password-tool.git"); // The SSH URL to your repository
define("DIR", ROOT_DIR);                          // The path to your repostiroy; this must begin with a forward slash (/)
define("BRANCH", "origin/master");                                 // The branch route
define("LOGFILE", "update.log");                                       // The name of the file you want to log to.
define("GIT", "/usr/bin/git");                                         // The path to the git executable
define("AFTER_PULL", "");                                              // A command to execute after successfully pulling