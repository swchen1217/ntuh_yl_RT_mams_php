<?php
// API
// $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME.';charset=utf8', DB_USER, DB_PASS);

require("config.php");
require("request.php");

mb_internal_encoding('UTF-8');

$mode = request("mode");
$LastModified = request("LastModified");
$josn_data = request("josn_data");
$id = request("id");
$acc = request("acc");
$pw = request("pw");
$DID = request("DID");
$user = request("user");
$position = request("position");
$status = request("status");

$key = array("DID", "category", "model", "number", "user", "position", "status", "LastModified");

if ($mode == "sync_device_tb_download") {
    if (UserCheck($acc, $pw, 1, $db)) {
        $sql = 'SELECT * FROM `device_tb` WHERE `LastModified` > :LastModified';
        $rs = $db->prepare($sql);
        $rs->bindValue(':LastModified', $LastModified, PDO::PARAM_STR);
        $rs->execute();
        if ($rs->rowCount() == 0) {
            echo "no_data";
        } else {
            $ToJson = array();
            while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $ToJson[] = $row;
            }
            echo json_encode($ToJson);
        }
    } else {
        echo "user_error";
    }
    exit;
}
if ($mode == "GetSystem_tb") {
    if ($id != "") {
        if (UserCheck($acc, $pw, 1, $db)) {
            $sql = 'SELECT value FROM `system_tb` WHERE id=:id';
            $rs = $db->prepare($sql);
            $rs->bindValue(':id', $id, PDO::PARAM_STR);
            $rs->execute();
            list($value_r) = $rs->fetch(PDO::FETCH_NUM);
            echo $value_r;
        } else {
            echo "user_error";
        }
    }
    exit;
}
if ($mode == "sync_position_item_tb_download") {
    if (UserCheck($acc, $pw, 1, $db)) {
        $sql = 'SELECT * FROM `position_item_tb` WHERE 1';
        $rs = $db->prepare($sql);
        $rs->execute();
        $ToJson = array();
        while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $ToJson[] = $row;
        }
        echo json_encode($ToJson);
    } else {
        echo "user_error";
    }
    exit;
}
if ($mode == "update_device_tb_use") {
    if (UserCheck($acc, $pw, 2, $db)) {
        if ($DID != "" && $user != "" && $position != "") {
            $sql = "UPDATE `device_tb` SET `user`=:user,`position`=:position,`status`='1',`LastModified`=:LastModified WHERE `DID`=:DID";
            $rs = $db->prepare($sql);
            $rs->bindValue(':user', $user, PDO::PARAM_STR);
            $rs->bindValue(':position', $position, PDO::PARAM_STR);
            $rs->bindValue(':LastModified', date("Y-m-d H:i:s", time()), PDO::PARAM_STR);
            $rs->bindValue(':DID', $DID, PDO::PARAM_STR);
            $rs->execute();
            echo "ok";
        }
    } else {
        echo "user_error";
    }
    exit;
}
if ($mode == "update_device_tb_storeroom") {
    if (UserCheck($acc, $pw, 2, $db)) {
        if ($DID != "" && $position != "") {
            $sql = "UPDATE `device_tb` SET `user`='-',`position`=:position,`status`='2',`LastModified`=:LastModified WHERE `DID`=:DID";
            $rs = $db->prepare($sql);
            $rs->bindValue(':position', '*' . $position, PDO::PARAM_STR);
            $rs->bindValue(':LastModified', date("Y-m-d H:i:s", time()), PDO::PARAM_STR);
            $rs->bindValue(':DID', $DID, PDO::PARAM_STR);
            $rs->execute();
            echo "ok";
        }
    } else {
        echo "user_error";
    }
    exit;
}
function UserCheck($acc_in, $pw_in, $permission_in, PDO $mDB)
{
    //require("config.php");
    if ($acc_in != "" && $pw_in != "") {
        $sql = 'SELECT password,permission FROM `user_tb` WHERE `account`=:acc_in';
        $rs = $mDB->prepare($sql);
        $rs->bindValue(':acc_in', $acc_in, PDO::PARAM_STR);
        $rs->execute();
        if ($rs->rowCount() == 0) {
            return false;
        } else {
            list($pw_r, $permission_r_first) = $rs->fetch(PDO::FETCH_NUM);
            if ($permission_r_first == "-1") {
                return false;
            } else {
                if (substr($pw_in, 0, 6) == "tmppw_") {
                    $sql2 = 'SELECT tmppw,application_time FROM `user_tmppw_tb` WHERE `account`=:acc_in order by application_time desc';
                    $rs2 = $mDB->prepare($sql2);
                    $rs2->bindValue(':acc_in', $acc_in, PDO::PARAM_STR);
                    $rs2->execute();
                    if ($rs2->rowCount() == 0)
                        return false;
                    else {
                        list($tmppw_r, $application_time_r) = $rs2->fetch(PDO::FETCH_NUM);
                        if ($pw_in == $tmppw_r) {
                            if ((strtotime(date("Y-m-d H:i:s", time())) - strtotime($application_time_r)) <= 1800) {
                                $sql3 = 'SELECT name,permission FROM `user_tb` WHERE `account`=:acc_in';
                                $rs3 = $mDB->prepare($sql3);
                                $rs3->bindValue(':acc_in', $acc_in, PDO::PARAM_STR);
                                $rs3->execute();
                                list($name_r, $permission_r) = $rs3->fetch(PDO::FETCH_NUM);
                                if ((int)$permission_r >= $permission_in)
                                    echo true;
                                else
                                    return false;
                            } else
                                return false;
                        } else
                            return false;
                    }
                } else {
                    if ($pw_r == $pw_in) {
                        $sql4 = 'SELECT name,permission FROM `user_tb` WHERE `account`=:acc_in';
                        $rs4 = $mDB->prepare($sql4);
                        $rs4->bindValue(':acc_in', $acc_in, PDO::PARAM_STR);
                        $rs4->execute();
                        list($name_r, $permission_r) = $rs4->fetch(PDO::FETCH_NUM);
                        if ((int)$permission_r >= $permission_in)
                            return true;
                        else
                            return false;
                    } else
                        return false;
                }
            }
        }
    } else
        return false;
}

?>