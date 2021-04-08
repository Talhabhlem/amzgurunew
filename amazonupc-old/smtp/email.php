<?php
require("PHPMailerAutoload.php");
class amazonEmail
{
    public function __construct()
    {

    }
    public function sendEmailWithAttachment($email,$file){
        $mail = new PHPMailer;

        $mail->isSMTP();
        $mail->Host = 'todd.toddsseeds.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'amazonbot@stats.ecommelite.com';
        $mail->Password = 'amazonbot';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->SMTPDebug = 2;

        $mail->setFrom('amazonbot@stats.ecommelite.com', 'Amazon UPC Bot');
        $mail->addAddress($email);
        $mail->addAttachment($file);
        $mail->isHTML(true);

        $mail->Subject = 'Amazon Upc Bot Result ('.date("Y-m-d h:i:s").')';
        $mail->Body = 'Please find the csv attachment along witht the email';
        $mail->AltBody = 'Please find the csv attachment along witht the email';

        if (!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
			return false;
        } else {
            echo 'Message has been sent';
			return true;
        }
    }
}
?>