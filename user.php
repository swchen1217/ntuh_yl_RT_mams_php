<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require("config.php");

require('./PHPMailer/src/Exception.php');
require('./PHPMailer/src/PHPMailer.php');
require('./PHPMailer/src/SMTP.php');

mb_internal_encoding('UTF-8');

if (!function_exists('ereg'))
{
    function ereg($pattern, $string, $regs)
    {
        return preg_match('/'.$pattern.'/', $string, $regs);
    }
}
 
if (!function_exists('eregi'))
{
    function eregi($pattern, $string, $regs)
    {
        return preg_match('/'.$pattern.'/i', $string, $regs);
    }
}

$mode = "";
if(isset($_REQUEST["mode"]))
	$mode=$_REQUEST["mode"];
$acc = "";
if(isset($_REQUEST["acc"]))
	$acc=$_REQUEST["acc"];
$pw = "";
if(isset($_REQUEST["pw"]))
	$pw=$_REQUEST["pw"];
$new_pw = "";
if(isset($_REQUEST["new_pw"]))
	$new_pw=$_REQUEST["new_pw"];
$email = "";
if(isset($_REQUEST["email"]))
	$email=$_REQUEST["email"];
$redirection="";
if(isset($_REQUEST["redirection"]))
	$redirection=$_REQUEST["redirection"];

if($mode=="connection_test"){
    echo "connection_ok";
}
if($mode=="login_check"){
    $sql = 'SELECT `password` FROM `user_tb` WHERE `account`="'.$acc.'"';
	$rs=mysqli_query($con,$sql);
    if(mysqli_num_rows($rs) == 0){
        echo "no_acc";
    }else{
        if(substr($pw,0,6)=="tmppw_"){
            $sql2 = 'SELECT tmppw,application_time FROM `user_tmppw_tb` WHERE `account`="'.$acc.'" order by application_time desc';
            $rs2=mysqli_query($con,$sql2);
            if(mysqli_num_rows($rs2) == 0)
                echo "tmppw_no_tmppw";
            else{
                list($tmppw_r,$application_time_r)=mysqli_fetch_row($rs2);
                if((strtotime(date("Y-m-d H:i:s",time())) - strtotime($application_time_r))<=1800){
                    if($pw==$tmppw_r)
                        echo "tmppw_ok";
                    else
                        echo "tmppw_error";
                }else
                    echo "tmppw_timeout";
            }
        }else{
            list($pw_r)=mysqli_fetch_row($rs);
            if($pw_r==$pw)
                echo "ok";
            else
                echo "pw_error";
        }
    }
    exit;
}
if($mode=="get_user_name"){
    $sql = 'SELECT `name` FROM `user_tb` WHERE `account`="'.$acc.'"';
	$rs=mysqli_query($con,$sql);
    list($name)=mysqli_fetch_row($rs);
    echo $name;
    exit;
}
if($mode=="check_has_email"){
    $sql = 'SELECT * FROM `user_tb` WHERE `email`="'.$email.'"';
	$rs=mysqli_query($con,$sql);
    if(mysqli_num_rows($rs) == 0){
        echo "no_email";
    }else{
        echo "has_email";
    }
    exit;
}
if($mode=="forget_pw"){
    $sql = 'SELECT name,account FROM `user_tb` WHERE `email`="'.$email.'"';
	$rs=mysqli_query($con,$sql);
    list($name,$acc_2)=mysqli_fetch_row($rs);
    $tmp_pw="tmppw_".substr(md5(uniqid(rand(), true)),0,8);
    
    $forget_pw_mail_body = $name.' 你好<br>員工編號(帳號):'.$acc_2.'<br>請使用以下連結更改密碼<br>可在有效時間內使用臨時密碼登入儀器管理系統<br>臨時密碼:'.$tmp_pw.'<br><font color=red>注意:臨時密碼與連結僅在<b>30分鐘</b>內有效,請在<b>30分鐘</b>內更改密碼,否則須重新申請</font><br><font color=red>注意:新密碼不允許以"<b>tmppw_</b>"為開頭</font><br>更改密碼連結:<a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc='.$acc_2.'&tmppw='.$tmp_pw.'">http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc='.$acc_2.'&tmppw='.$tmp_pw.'</a>';
    
    $sql2 = "insert into user_tmppw_tb(account,tmppw,application_time)values('".$acc_2."','".$tmp_pw."','".date("Y-m-d H:i:s",time())."')";
    mysqli_query($con,$sql2);
    
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
        $mail->addAddress($email);
        $mail->addReplyTo('ntuhyl.mdms@gmail.com', 'NTUH.YL 儀器管理系統');
        $mail->isHTML(true);
        $mail->Subject = 'NTUH.YL 儀管系統 忘記密碼';
        $mail->Body= $forget_pw_mail_body;
        $mail->send();
        echo '寄信成功';
        if($redirection=="true"){
            header('Location: http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?redirection_ok=true&acc='.$acc_2);
            exit;
        }
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
    exit;
}
?>