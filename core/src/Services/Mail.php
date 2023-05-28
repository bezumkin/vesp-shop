<?php

namespace App\Services;

use Pelago\Emogrifier\CssInliner;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    public function send(string $to, string $subject, string $tpl, ?array $data = []): ?string
    {
        if (!getenv('SMTP_HOST')) {
            return null;
        }
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        // Transport
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = (bool)getenv('SMTP_USER');
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = getenv('SMTP_PROTO');
        $mail->Port = getenv('SMTP_PORT');
        $mail->SMTPDebug = 0;

        $mail->isHTML();
        $mail->Subject = $subject;

        try {
            // Recipients
            $mail->addAddress($to);
            $mail->setFrom(getenv('SMTP_USER'), getenv('SMTP_USER_NAME'));

            // Content
            $body = (new Fenom())->fetch($tpl, $data);
            $mail->Body = CssInliner::fromHtml($body)->inlineCss()->render();
            $mail->AltBody = $mail->html2text(nl2br($mail->Body));

            $mail->send();

            return null;
        } catch (Exception $e) {
            return $mail->ErrorInfo;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
