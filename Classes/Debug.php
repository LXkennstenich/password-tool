<?php

/**
 * PassTool
 * @version 1.0
 * @author Alexander Weese
 * @package PassTool
 * @copyright (c) 2018, Alexander Weese
 * @var $factory Factory
 * @var $session Session
 */
class Debug {

    public function log($data) {

        $file = LOG_DIR . 'log.txt';

        $logData = '';

        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************';
        $logData .= "\n";
        $logData .= "\n";
        $logData .= '[***UHRZEIT:*** ' . date('d-m-Y H:i:s', time()) . ' *** ' . $data;
        $logData .= "\n";
        $logData .= "\n";

        $writeLog = file_put_contents($file, $logData, FILE_APPEND);

        if ($writeLog === false) {
            return false;
        }

        return true;
    }

    public function databaseLog($data) {
        $file = LOG_DIR . 'databaselog.txt';

        $logData = '';

        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************';
        $logData .= "\n";
        $logData .= "\n";
        $logData .= '[***UHRZEIT:*** ' . date('d-m-Y H:i:s', time()) . ' *** ' . $data;
        $logData .= "\n";
        $logData .= "\n";


        $writeLog = file_put_contents($file, $logData, FILE_APPEND);

        if ($writeLog === false) {
            return false;
        }

        return true;
    }

    public function cronLog($data) {
        $file = LOG_DIR . 'cronlog.txt';

        $logData = '';

        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************' . "\n";
        $logData .= '********************************************************************************************************';
        $logData .= "\n";
        $logData .= "\n";
        $logData .= '[***UHRZEIT:*** ' . date('d-m-Y H:i:s', time()) . ' *** ' . $data;
        $logData .= "\n";
        $logData .= "\n";


        $writeLog = file_put_contents($file, $logData, FILE_APPEND);

        if ($writeLog === false) {
            return false;
        }

        return true;
    }

    public function printError($data) {
        $error = '<hr>';
        $error .= '<br>';
        $error .= $data;
        $error .= '<br>';
        $error .= '<hr>';

        return $error;
    }

}
