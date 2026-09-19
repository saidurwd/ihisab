<?php

class GmailMailer extends CApplicationComponent
{
    public $host = 'smtp.gmail.com';
    public $port = 465;
    public $username;
    public $password;
    public $fromEmail;
    public $fromName = 'iHisab';
    public $secure = 'ssl';

    public function sendEmail($to, $subject, $htmlBody, $textBody = null)
    {
        $textBody = $textBody ?: strip_tags($htmlBody);
        $fromEmail = $this->fromEmail;
        $fromName = $this->fromName ?: $this->username;

        $boundary = '----=_NextPart_' . md5(time() . $fromEmail);

        $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers = "From: {$fromName}<{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "Subject: {$subject}\r\n";
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

        $body = "--{$boundary}--\r\n";

        $crlf = "\r\n";
        $toHeader = str_replace($crlf, '', $to);

        $context = stream_context_create(array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            )
        ));

        $remote = 'ssl://' . $this->host;
        $socket = @stream_socket_client($remote . ':' . $this->port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
        if (!$socket) {
            Yii::log("SMTP connect failed: {$errstr} ({$errno})", CLogger::LEVEL_ERROR, 'mail');
            return false;
        }

        $line = fgets($socket, 515);
        if (strpos($line, '220') !== 0) {
            Yii::log("SMTP greeting failed: {$line}", CLogger::LEVEL_ERROR, 'mail');
            fclose($socket);
            return false;
        }

        $this->smtpCommand($socket, 'HELO ' . $this->host);
        $this->smtpCommand($socket, 'AUTH LOGIN');
        $this->smtpCommand($socket, base64_encode($this->username));
        $this->smtpCommand($socket, base64_encode($this->password));
        $this->smtpCommand($socket, 'MAIL FROM:<' . $fromEmail . '>');
        $this->smtpCommand($socket, 'RCPT TO:<' . $toHeader . '>');
        $this->smtpCommand($socket, 'DATA');
        fwrite($socket, "To: {$toHeader}{$crlf}" . $headers . $crlf . $body . $crlf . ".\r\n");
        $this->smtpCommand($socket, '.');
        $this->smtpCommand($socket, 'QUIT');
        fclose($socket);

        return true;
    }

    protected function smtpCommand($socket, $command)
    {
        fwrite($socket, $command . "\r\n");
        $line = fgets($socket, 515);
        if (strpos($line, '250') !== 0 && strpos($line, '354') !== 0 && strpos($line, '235') !== 0) {
            Yii::log("SMTP error: {$line} for command: {$command}", CLogger::LEVEL_ERROR, 'mail');
        }
        return $line;
    }
}
