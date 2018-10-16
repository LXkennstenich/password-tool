<?php

/**
 * PassTool
 * Tool zum sicheren verwalten von Passwörtern
 * @author Alexander Weese
 * @copyright (c) 2018, Alexander Weese
 */
class Mail {

    /**
     *
     * @var PHPMailer 
     */
    protected $phpMailer;

    /**
     *
     * @var string 
     */
    protected $from;

    public function __construct() {
        $config = require CONFIG_DIR . 'mailConfig.php';
        $this->setPHPMailer(new PHPMailer(true));
        $this->getPHPMailer()->SMTPDebug = $config['debugLevel'];
        $this->getPHPMailer()->Host = $config['mailServer'];
        $this->getPHPMailer()->SMTPAuth = $config['auth'];
        $this->getPHPMailer()->SMTPSecure = $config['secure'];
        $this->getPHPMailer()->Username = $config['username'];
        $this->getPHPMailer()->Mailer = 'smtp';
        $this->setFrom($config['username']);
        $this->getPHPMailer()->Password = $config['password'];
        $this->getPHPMailer()->SMTPSecure = $config['secure'];
        $this->getPHPMailer()->Port = $config['port'];
        $this->getPHPMailer()->isHTML(true);
    }

    public function setPHPMailer(\PHPMailer $phpMailer) {
        $this->phpMailer = $phpMailer;
    }

    public function getPHPMailer(): \PHPMailer {
        return $this->phpMailer;
    }

    public function setFrom(string $from) {
        $this->from = $from;
    }

    public function getFrom(): string {
        return $this->from;
    }

    public function sendMail($subject, $message, $recepient) {
        try {
            $this->getPHPMailer()->setFrom($this->getFrom());
            $this->getPHPMailer()->addAddress($recepient);
            $this->getPHPMailer()->Subject = $subject;
            $this->getPHPMailer()->Body = $message;
            $this->getPHPMailer()->AltBody = $message;

            $this->getPHPMailer()->send();
        } catch (Exception $ex) {
            if (SYSTEM_MODE == 'DEV') {
                $this->getDebugger()->printError($ex->getMessage());
            }

            $this->getDebugger()->databaselog('Ausnahme: ' . $this->getPHPMailer()->ErrorInfo . ' Zeile: ' . __LINE__ . ' Datei: ' . __FILE__ . ' Klasse: ' . __CLASS__);
        }
    }

}
