<?php
// API
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("config.php");
require("request.php");

require('./PHPMailer/src/Exception.php');
require('./PHPMailer/src/PHPMailer.php');
require('./PHPMailer/src/SMTP.php');

mb_internal_encoding('UTF-8');

if (!function_exists('ereg')) {
    function ereg($pattern, $string, $regs)
    {
        return preg_match('/' . $pattern . '/', $string, $regs);
    }
}
if (!function_exists('eregi')) {
    function eregi($pattern, $string, $regs)
    {
        return preg_match('/' . $pattern . '/i', $string, $regs);
    }
}
$mode = request("mode");
$acc = request("acc");
$pw = request("pw");
$new_pw = request("new_pw");
$email = request("email");
$redirection = request("redirection");
$token = request("token");
$old_pw = request("old_pw");

if ($mode == "connection_test") {
    echo "connection_ok";
}
if ($mode == "login_check") {
    $sql = "SELECT password,permission,name FROM `user_tb` WHERE `account`=:acc";
    $rs = $db->prepare($sql);
    $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
    $rs->execute();
    list($pw_r, $permission_r, $name_r) = $rs->fetch(PDO::FETCH_NUM);
    if ($pw_r == $pw) {
        if ($permission_r != '0') {
            echo 'ok,' . $name_r . ',' . $permission_r;
        } else
            echo 'no_enable';
    } else
        echo "pw_error";
    exit;
}
if ($mode == "get_user_name") {
    $sql = 'SELECT `name` FROM `user_tb` WHERE `account`=:acc';
    $rs = $db->prepare($sql);
    $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
    $rs->execute();
    list($name) = $rs->fetch(PDO::FETCH_NUM);
    echo $name;
    exit;
}
if ($mode == "check_has_email") {
    $sql = 'SELECT * FROM `user_tb` WHERE `email`=:email';
    $rs = $db->prepare($sql);
    $rs->bindValue(':email', $email, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() == 0) {
        echo "no_email";
    } else {
        echo "has_email";
    }
    exit;
}
if ($mode == "forget_pw") {
    $sql = 'SELECT name,account FROM `user_tb` WHERE `email`=:email';
    $rs = $db->prepare($sql);
    $rs->bindValue(':email', $email, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() == 0) {
        echo 'email_not_exist';
    } else {
        list($name_r, $acc_r) = $rs->fetch(PDO::FETCH_NUM);
        $token = md5(uniqid($acc_r));
        $sql2 = "insert into rstpw_token_tb(account,token,apply_time)values(:acc_r,:token,:d)";
        $rs2 = $db->prepare($sql2);
        $rs2->bindValue(':acc_r', $acc_r, PDO::PARAM_STR);
        $rs2->bindValue(':token', $token, PDO::PARAM_STR);
        $rs2->bindValue(':d', date("Y-m-d H:i:s", time()), PDO::PARAM_STR);
        $rs2->execute();

        $forget_pw_mail_body = $name_r . ' 你好<br>員工編號(帳號):' . $acc_r . '<br>請使用以下連結更改密碼<br><span style="color: red; ">注意:連結僅在<b>30分鐘</b>內有效,請在<b>30分鐘</b>內更改密碼,否則須重新申請</span><br>更改密碼連結:<a href="' . SERVER_IP_WEB . '/index.html?token=' . $token . '#ChangePw">' . SERVER_IP_WEB . '/change_pw.php?token=' . $token . '#ChangePw</a>';

        $mail = new PHPMailer(true);
        try {
            //$mail->SMTPDebug = 3;
            $mail->isSMTP();
            $mail->Charset = 'UTF-8';
            $mail->Host = 'ssl://smtp.gmail.com:465';
            $mail->SMTPAuth = true;
            $mail->Username = MAIL_USER;
            $mail->Password = MAIL_PASS;
            $mail->setFrom('ntuhyl.mdms@gmail.com', 'NTUH.YL 醫療儀器管理系統');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'NTUH.YL 儀管系統 忘記密碼';
            $mail->Body = $forget_pw_mail_body;
            $mail->send();
            echo '寄信成功';
        } catch (Exception $e) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
        exit;
    }


    // OLD
    /*$sql = 'SELECT name,account FROM `user_tb` WHERE `email`=:email';
    $rs = $db->prepare($sql);
    $rs->bindValue(':email', $email, PDO::PARAM_STR);
    $rs->execute();
    list($name, $acc_2) = $rs->fetch(PDO::FETCH_NUM);
    $tmp_pw = "tmppw_" . substr(md5(uniqid(rand(), true)), 0, 8);

    $forget_pw_mail_body = $name . ' 你好<br>員工編號(帳號):' . $acc_2 . '<br>請使用以下連結更改密碼<br>可在有效時間內使用臨時密碼登入醫療儀器管理系統<br>臨時密碼:' . $tmp_pw . '<br><font color=red>注意:臨時密碼與連結僅在<b>30分鐘</b>內有效,請在<b>30分鐘</b>內更改密碼,否則須重新申請</font><br><font color=red>注意:新密碼不允許以"<b>tmppw_</b>"為開頭</font><br>更改密碼連結:<a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc=' . $acc_2 . '&tmppw=' . $tmp_pw . '">http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc=' . $acc_2 . '&tmppw=' . $tmp_pw . '</a>';

    $sql2 = "insert into user_tmppw_tb(account,tmppw,application_time)values(:acc_2,:tmp_pw,:d)";
    $rs2 = $db->prepare($sql2);
    $rs2->bindValue(':acc_2', $acc_2, PDO::PARAM_STR);
    $rs2->bindValue(':tmp_pw', $tmp_pw, PDO::PARAM_STR);
    $rs2->bindValue(':d', date("Y-m-d H:i:s", time()), PDO::PARAM_STR);
    $rs2->execute();

    $mail = new PHPMailer(true);
    try {
        //$mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Charset = 'UTF-8';
        $mail->Host = 'ssl://smtp.gmail.com:465';
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;
        $mail->setFrom('ntuhyl.mdms@gmail.com', 'NTUH.YL 醫療儀器管理系統');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'NTUH.YL 儀管系統 忘記密碼';
        $mail->Body = $forget_pw_mail_body;
        $mail->send();
        echo '寄信成功';
        if ($redirection == "true") {
            header('Location: http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?redirection_ok=true&acc=' . $acc_2);
            exit;
        }
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    exit;*/
}
if ($mode == "get_create_time") {
    $sql = 'SELECT `created` FROM `user_tb` WHERE `account`=:acc';
    $rs = $db->prepare($sql);
    $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() != 0) {
        list($create_time) = $rs->fetch(PDO::FETCH_NUM);
        echo date('YmdHis', strtotime($create_time));
    } else {
        echo 'no_acc';
    }
    exit;
}
if ($mode == "rstpw_check") {
    $sql = 'SELECT `apply_time`,`account` FROM `rstpw_token_tb` WHERE `token`=:token';
    $rs = $db->prepare($sql);
    $rs->bindValue(':token', $token, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() != 0) {
        list($time_r, $acc_r) = $rs->fetch(PDO::FETCH_NUM);
        if ((strtotime(date("Y-m-d H:i:s", time())) - strtotime($time_r)) <= 1800) {
            $sql2 = 'SELECT `created` FROM `user_tb` WHERE `account`=:acc';
            $rs2 = $db->prepare($sql2);
            $rs2->bindValue(':acc', $acc_r, PDO::PARAM_STR);
            $rs2->execute();
            list($create_time_r) = $rs2->fetch(PDO::FETCH_NUM);
            echo "token_ok," . $acc_r . "," . date('YmdHis', strtotime($create_time_r));
        } else {
            echo "token_timeout";
        }
    } else {
        echo 'hasnot_token';
    }
    exit;
}
if ($mode == "rstpw_submit") {
    $sql = 'SELECT `apply_time`,`account` FROM `rstpw_token_tb` WHERE `token`=:token';
    $rs = $db->prepare($sql);
    $rs->bindValue(':token', $token, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() != 0) {
        list($time_r, $acc_r) = $rs->fetch(PDO::FETCH_NUM);
        if ((strtotime(date("Y-m-d H:i:s", time())) - strtotime($time_r)) <= 1800) {
            $sql2 = 'UPDATE `user_tb` SET `password`=:npw WHERE `account`=:acc';
            $rs2 = $db->prepare($sql2);
            $rs2->bindValue(':npw', $new_pw, PDO::PARAM_STR);
            $rs2->bindValue(':acc', $acc_r, PDO::PARAM_STR);
            $rs2->execute();
            $sql3 = 'DELETE FROM `rstpw_token_tb` WHERE `account`=:acc';
            $rs3 = $db->prepare($sql3);
            $rs3->bindValue(':acc', $acc_r, PDO::PARAM_STR);
            $rs3->execute();
            echo "rstpw_ok";
        } else {
            echo "token_timeout";
        }
    } else {
        echo 'hasnot_token';
    }
    exit;
}
if($mode=="chgpw"){
    $sql = 'SELECT `password` FROM `user_tb` WHERE `account`=:acc';
    $rs = $db->prepare($sql);
    $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
    $rs->execute();
    list($pw_r) = $rs->fetch(PDO::FETCH_NUM);
    if($old_pw!=$pw_r){
        echo "old_pw_error";
    }else{
        $sql2 = 'UPDATE `user_tb` SET `password`=:npw WHERE `account`=:acc';
        $rs2 = $db->prepare($sql2);
        $rs2->bindValue(':npw', $new_pw, PDO::PARAM_STR);
        $rs2->bindValue(':acc', $acc, PDO::PARAM_STR);
        $rs2->execute();
        echo "ok";
    }
    exit;
}
?>