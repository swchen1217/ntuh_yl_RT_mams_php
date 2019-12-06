<?php
// API
// $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_DBNAME.';charset=utf8', DB_USER, DB_PASS);

require("config.php");
require("request.php");
require("UserCheck.php");

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
if($mode=="del_position"){
    if (UserCheck($acc, $pw, 4, $db)) {
        $tmp=explode('-',$position);
        $sql = "DELETE FROM `position_item_tb` WHERE `type`=:type AND `item`=:item";
        $rs = $db->prepare($sql);
        $rs->bindValue(':type', $tmp[0], PDO::PARAM_STR);
        $rs->bindValue(':item', $tmp[1], PDO::PARAM_STR);
        $rs->execute();
        echo "ok";
    } else {
        echo "user_error";
    }
    exit;
}

?>