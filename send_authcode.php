<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions


function send_email($user_email, $subject, $body)
{
    try {
        //Server settings
        $mail = new PHPMailer(true);
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.yandex.ru';                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'no-reply@ak-vps.tk';                     // SMTP username
        $mail->Password = 'apcrcugbiogpyhau';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom($mail->Username, 'Евразия Онлайн CRM');
        $mail->addAddress($user_email, '');     // Add a recipient
        $mail->CharSet = 'UTF-8';
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = $body;

        $mail->send();
        http_response_code(200);
        return true;
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(array("message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"));
    }
}

