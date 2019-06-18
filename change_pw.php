<center>
<h2>NTUH.YL 儀器管理系統</h2>
<h3>更改登入密碼</h3>
<meta http-equiv="Pragma" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php
require("config.php");

$tmppw = "";
if(isset($_GET["tmppw"]))
	$tmppw=$_GET["tmppw"];
$acc = "";
if(isset($_GET["acc"]))
	$acc=$_GET["acc"];
$input_acc = "";
if(isset($_POST["input_acc"]))
	$input_acc=$_POST["input_acc"];
$input_old_pw = "";
if(isset($_POST["input_old_pw"]))
	$input_old_pw=$_POST["input_old_pw"];
$input_new_pw = "";
if(isset($_POST["input_new_pw"]))
	$input_new_pw=$_POST["input_new_pw"];
$input_new_pw_re = "";
if(isset($_POST["input_new_pw_re"]))
	$input_new_pw_re=$_POST["input_new_pw_re"];
$redirection_ok = "";
if(isset($_GET["redirection_ok"]))
	$redirection_ok=$_GET["redirection_ok"];

if($redirection_ok=="true" && $acc!=""){
    echo '<font color=blue><b>成功:已重新申請更改密碼</b></font><br>員工編號(帳號):'.$acc.'<br>請至Email信箱使用更改密碼連結重新更改';
}else{
    if($input_new_pw==""){
        if($tmppw!="" && $acc!=""){
        ?>
            <form action="" method="post">
                &emsp;員工編號：<input type="text" name="input_acc" required placeholder="請輸入員工編號(帳號)" value=<?php echo $acc; ?> disabled="disabled" ><br><br>
                &emsp;臨時密碼：<input type="text" name="input_tmppw" required placeholder="tmppw_**********" value=<?php echo $tmppw; ?> disabled="disabled" ><br><br>
                &emsp;&emsp;新密碼：<input type="password" name="input_new_pw" required placeholder="請設定新密碼"><br><br>
                確認新密碼：<input type="password" name="input_new_pw_re" required placeholder="請再次輸入新密碼"><br><br>
                <input type="submit" value="確認更改">
            </form>
        <?php
        }else{
        ?>
            <form action="" method="post">
                &emsp;員工編號：<input type="text" name="input_acc" required placeholder="請輸入員工編號(帳號)"><br><br>
                &emsp;&emsp;原密碼：<input type="text" name="input_old_pw" required placeholder="請輸入原密碼"><br><br>
                &emsp;&emsp;新密碼：<input type="password" name="input_new_pw" required placeholder="請設定新密碼"><br><br>
                確認新密碼：<input type="password" name="input_new_pw_re" required placeholder="請再次輸入新密碼"><br><br>
                <input type="submit" value="確認更改">
            </form>
        <?php
        }
        echo '<font color=red><b>注意:新密碼不允許以"tmppw_"為開頭</b></font>';
    }else{
        if($tmppw!=""){
            $sql = 'SELECT application_time FROM `user_tmppw_tb` WHERE `account`="'.$acc.'" and `tmppw`="'.$tmppw.'" order by application_time desc';
            $rs=mysqli_query($con,$sql);
            if(mysqli_num_rows($rs) == 0){
                echo "<font color=red><b>錯誤:先前已完成修改</b></font>";
            }else{
                list($time)=mysqli_fetch_row($rs);
                if((strtotime(date("Y-m-d H:i:s",time())) - strtotime($time))<=1800){
                    if($input_new_pw==$input_new_pw_re){
                        if(substr($input_new_pw,0,6)!="tmppw_"){
                            $sql2 = 'UPDATE `user_tb` SET `password`="'.$input_new_pw.'" WHERE `account`="'.$acc.'"';
                            mysqli_query($con,$sql2);
                            $sql4 = 'DELETE FROM `user_tmppw_tb`WHERE `account`="'.$acc.'"';
                            mysqli_query($con,$sql4);
                            echo "<font color=blue><b>成功:已成功更改密碼</b></font>";
                        }else{
                            echo '<font color=red><b>錯誤:新密碼不允許以"tmppw_"為開頭<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc='.$acc.'&tmppw='.$tmppw.'">回上頁</a>';
                        }
                    }else{
                        echo '<font color=red><b>錯誤:新密碼與確認新密碼不相符<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php?acc='.$acc.'&tmppw='.$tmppw.'">回上頁</a>';
                    }
                }else{
                    $sql3 = 'SELECT email FROM `user_tb` WHERE `account`="'.$acc.'"';
                    $rs3=mysqli_query($con,$sql3);
                    list($email)=mysqli_fetch_row($rs3);
                    echo '<font color=red><b>錯誤:此臨時密碼已超過有效時間<br>請重新申請</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/user.php?mode=forget_pw&email='.$email.'&redirection=true">重新申請</a>';
                }
            }
        }else{
            $sql5 = 'SELECT account,password FROM `user_tb` WHERE `account`="'.$input_acc.'"';
            $rs5=mysqli_query($con,$sql5);
            if(mysqli_num_rows($rs5) == 0){
                echo '<font color=red><b>錯誤:此員工編號尚未註冊<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php">回上頁</a>';
            }else{
                list($db_acc,$db_pw)=mysqli_fetch_row($rs5);
                if($input_old_pw==$db_pw){
                    if($input_new_pw==$input_new_pw_re){
                        if(substr($input_new_pw,0,6)!="tmppw_"){
                            $sql6 = 'UPDATE `user_tb` SET `password`="'.$input_new_pw.'" WHERE `account`="'.$input_acc.'"';
                            mysqli_query($con,$sql6);
                            $sql7 = 'DELETE FROM `user_tmppw_tb`WHERE `account`="'.$input_acc.'"';
                            mysqli_query($con,$sql7);
                            echo "<font color=blue><b>成功:已成功更改密碼</b></font>";
                        }else{
                            echo '<font color=red><b>錯誤:新密碼不允許以"tmppw_"為開頭<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php">回上頁</a>';
                        }
                    }else{
                        echo '<font color=red><b>錯誤:新密碼與確認新密碼不相符<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php">回上頁</a>';
                    }
                }else{
                    echo '<font color=red><b>錯誤:原密碼輸入錯誤<br>請重新輸入</b></font><br><a href="http://swchen1217.ddns.net/ntuh_yl_RT_mdms_php/change_pw.php">回上頁</a>';
                }
            }
        }
    }
}
?>
</center>