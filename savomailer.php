<?php
require 'vendor/autoload.php';
include_once 'mail_credentials.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SavoMailer {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = USERNAME;
        $this->mailer->Password = PASSWORD;
        $this->mailer->SMTPSecure = SMTPSECURE;
        $this->mailer->Port = PORT;
        $this->mailer->setFrom(SENDERADR, SENDERNAME);
        $this->mailer->isHTML(true);
    }

    public function send($subject, $body) {
        try {
            $this->mailer->addAddress(ADMINADR);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}