<?php
function UserCheck($acc_in, $pw_in, $permission_in, PDO $mDB)
{
    if ($acc_in != "" && $pw_in != "") {
        $sql = "SELECT password,permission FROM `user_tb` WHERE `account`=:acc";
        $rs = $mDB->prepare($sql);
        $rs->bindValue(':acc', $acc_in, PDO::PARAM_STR);
        $rs->execute();
        list($pw_r, $permission_r) = $rs->fetch(PDO::FETCH_NUM);
        if($pw_r==$pw_in){
            if($permission_r!='0'){
                return true;
            }else
                return false;
        }else
            return false;
    } else
        return false;
}
?>