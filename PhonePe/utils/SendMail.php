<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/autoload.php';

class Mail
{
    function sendMail($to = '', $msg = '')
    {
        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 2;
            $mail->isSMTP();
            $mail->Host     = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 't95290595@gmail.com';
            $mail->Password = 'xjtebhdnyxagurdq';
            $mail->SMTPSecure = 'ssl';
            $mail->Port     = 465;
            $mail->setFrom('t95290595@gmail.com', 'Purshottam'); // Corrected this line
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = 'Payment Successfully Completed';
            $mail->Body = $msg;

            return $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
