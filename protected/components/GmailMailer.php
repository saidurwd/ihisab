<?php

class GmailMailer extends CApplicationComponent
{
    public $host = 'smtp.gmail.com';
    public $port = 587;
    public $username;
    public $password;
    public $fromEmail;
    public $fromName = 'iHisab';
    public $secure = 'tls';

    public function sendEmail($to, $subject, $htmlBody, $textBody = null)
    {
        $textBody = $textBody ?: strip_tags($htmlBody);
        $fromEmail = $this->fromEmail;
        $fromName = $this->fromName;

        $boundary = '----=_NextPart_' . md5(time() . $fromEmail);

        $headers = "From: {$fromName}<{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n";

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($textBody)) . "\r\n";

        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= chunk_split(base64_encode($htmlBody)) . "\r\n";

        $body .= "--{$boundary}--\r\n";

        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        return mail($to, $subject, $body, $headers, "-f{$fromEmail}");
    }
}
