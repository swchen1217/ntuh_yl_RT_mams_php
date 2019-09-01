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

if ($mode == "connection_test") {
    echo "connection_ok";
}
if ($mode == "login_check") {
    $sql = "SELECT password,permission FROM `user_tb` WHERE `account`=:acc";
    $rs = $db->prepare($sql);
    $rs->bindValue(':acc', $acc, PDO::PARAM_STR);
    $rs->execute();
    if ($rs->rowCount() == 0) {
        echo "no_acc";
        $db = null;
        exit;
    } else {
        list($pw_r, $permission_r_first) = $rs->fetch(PDO::FETCH_NUM);
        if ($permission_r_first == "-1") {
            echo "no_acc";
        } else {
            if (substr($pw, 0, 6) == "tmppw_") {
                $sql2 = 'SELECT tmppw,application_time FROM `user_tmppw_tb` WHERE `account`=:acc order by application_time desc';
                $rs2 = $db->prepare($sql2);
                $rs2->bindValue(':acc', $acc, PDO::PARAM_STR);
                $rs2->execute();
                if ($rs2->rowCount() == 0)
                    echo "tmppw_no_tmppw";
                else {
                    list($tmppw_r, $application_time_r) = $rs2->fetch(PDO::FETCH_NUM);
                    if ($pw == $tmppw_r) {
                        if ((strtotime(date("Y-m-d H:i:s", time())) - strtotime($application_time_r)) <= 1800) {
                            $sql3 = 'SELECT name,permission FROM `user_tb` WHERE `account`=:acc';
                            $rs3 = $db->prepare($sql3);
                            $rs3->bindValue(':acc', $acc, PDO::PARAM_STR);
                            $rs3->execute();
                            list($name_r, $permission_r) = $rs3->fetch(PDO::FETCH_NUM);
                            if ($permission_r != "0")
                                echo 'ok_tmppw,' . $name_r . ',' . $permission_r;
                            else
                                echo 'no_enable';
                        } else
                            echo "tmppw_timeout";
                    } else
                        echo "tmppw_error";
                }
            } else {
                if ($pw_r == $pw) {
                    $sql4 = 'SELECT name,permission FROM `user_tb` WHERE `account`=:acc';
                    $rs4 = $db->prepare($sql4);
                    $rs4->bindValue(':acc', $acc, PDO::PARAM_STR);
                    $rs4->execute();
                    list($name_r, $permission_r) = $rs4->fetch(PDO::FETCH_NUM);
                    if ($permission_r != "0")
                        echo 'ok,' . $name_r . ',' . $permission_r;
                    else
                        echo 'no_enable';
                } else
                    echo "pw_error";
            }
        }
    }
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
        require("mail_config.php");
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
    exit;
}
?>