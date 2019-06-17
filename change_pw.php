<center>
<h2>NTUH.YL 儀器管理系統</h2>
<h3>更改登入密碼</h3>
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

if($input_new_pw==""){
    if($tmppw!="" && $acc!=""){
    ?>
        <form action="" method="post">
            &emsp;員工編號：<input type="text" name="input_acc" required placeholder="請輸入員工編號(帳號)" value=<?php echo $acc; ?> disabled="disabled"><br><br>
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
}else{
    if($tmppw!=""){
        $sql = 'SELECT application_time FROM `user_tmppw_tb` WHERE `account`="'.$acc.'" and `tmppw`="'.$tmppw.'" order by application_time desc';
        $rs=mysqli_query($con,$sql);
        if(mysqli_num_rows($rs) == 0){
            echo "<font color=red><b>錯誤:請重新申請</b></font>";
        }else{
            list($time)=mysqli_fetch_row($rs);
            if((strtotime(date("Y-m-d H:i:s",time())) - strtotime($time))<=1800){
                if($input_new_pw==$input_new_pw_re){
                    $sql2 = 'UPDATE `user_tb` SET `password`="'.$input_new_pw.'" WHERE `account`="'.$acc.'"';
                    $rs=mysqli_query($con,$sql2);
                }else{
                    
                }
            }
        }
    }else{
        
    }
    //echo $input_acc.$input_tmppw.$input_old_pw.$input_new_pw.$input_new_pw_re;
}
//echo $tmppw;
?>
</center>