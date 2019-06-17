<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('./PHPMailer/src/Exception.php');
require('./PHPMailer/src/PHPMailer.php');
require('./PHPMailer/src/SMTP.php');

mb_internal_encoding('UTF-8');

$mail = new PHPMailer(true);
try {
    //$mail->SMTPDebug = 3;
    $mail->isSMTP();
    $mail->Charset='UTF-8';
    $mail->Host = 'ssl://smtp.gmail.com:465';
    $mail->SMTPAuth = true;
    $mail->Username = 'ntuhyl.mdms@gmail.com';
    $mail->Password = 'ntuhntuh';
    $mail->setFrom('ntuhyl.mdms@gmail.com', 'NTUH.YL 儀器管理系統');
    $mail->addAddress('swchen1217@gmail.com');
    $mail->addReplyTo('ntuhyl.mdms@gmail.com', 'NTUH.YL 儀器管理系統');
    $mail->isHTML(true);
    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->send();
        echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>