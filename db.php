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
$status = request("status",true);
$operate_DID = request("operate_DID");
$new_category = request("new_category");
$new_model = request("new_model");
$new_number = request("new_number");
$new_user = request("new_user");
$new_position = request("new_position");
$new_status = request("new_status",true);


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
if ($mode == "getDeviceData") {
    if (UserCheck($acc, $pw, 1, $db)) {
        $sql = 'SELECT * FROM `device_tb` WHERE status!=-1';
        $rs = $db->prepare($sql);
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
if ($mode == "del_position") {
    if (UserCheck($acc, $pw, 4, $db)) {
        $tmp = explode('-', $position);
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
if ($mode == "new_position"){
    if (UserCheck($acc, $pw, 4, $db)) {
        $tmp = explode('-', $position);
        $sql = "INSERT INTO `position_item_tb`(`type`, `item`) VALUES(:type,:item)";
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
if($mode=="newdevice"){
    if(UserCheck($acc,$pw,4,$db)){
        $sql = 'INSERT INTO `device_tb` (did, category, model, number, status, lastmodified) VALUES (:DID, :category, :model, :number, :status, :LastModified)';
        $rs = $db->prepare($sql);
        $rs->bindValue(':DID', getDID($db), PDO::PARAM_STR);
        $rs->bindValue(':category', $new_category, PDO::PARAM_STR);
        $rs->bindValue(':model', $new_model, PDO::PARAM_STR);
        $rs->bindValue(':number', $new_number, PDO::PARAM_STR);
        $rs->bindValue(':status', '0', PDO::PARAM_STR);
        $rs->bindValue(':LastModified', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        $rs->execute();
        echo "ok";
    }else{
        echo "error";
    }
    exit;
}

if($mode=="chgdevice"){
    if(UserCheck($acc,$pw,4,$db)){
        $data="";
        if($new_category!="")
            $data .= "`category`=:category,";
        if($new_model!="")
            $data .= "`model`=:model,";
        if($new_number!=""){
            $sqlT = 'SELECT * FROM `device_tb` WHERE number=:mNum';
            $rsT = $db->prepare($sqlT);
            $rsT->bindValue(':mNum', $new_number, PDO::PARAM_STR);
            $rsT->execute();
            if ($rsT->rowCount() != 0){
                echo "number_error";
                exit;
            }
            $data .= "`number`=:number,";
        }
        if($new_user!="")
            $data .= "`user`=:user,";
        if($new_position!="")
            $data .= "`position`=:position,";
        if($new_status!="")
            $data .= "`status`=:status,";
        $data .= "`LastModified`=:LastModified,";
        $data=substr($data,0,-1);
        $sql = 'UPDATE `device_tb` SET' . $data .' WHERE `DID`=:DID';
        $rs = $db->prepare($sql);
        $rs->bindValue(':DID', $operate_DID, PDO::PARAM_STR);
        $rs->bindValue(':LastModified', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        if($new_category!="")
            $rs->bindValue(':category', $new_category, PDO::PARAM_STR);
        if($new_model!="")
            $rs->bindValue(':model', $new_model, PDO::PARAM_STR);
        if($new_number!="")
            $rs->bindValue(':number', $new_number, PDO::PARAM_STR);
        if($new_user!="")
            $rs->bindValue(':user', $new_user, PDO::PARAM_STR);
        if($new_position!="")
            $rs->bindValue(':position', $new_position, PDO::PARAM_STR);
        if($new_status!="")
            $rs->bindValue(':status', $new_status, PDO::PARAM_STR);
        $rs->execute();
        echo "ok";
    }else{
        echo "error";
    }
    exit;
}

function getDID(PDO $mDB){
    $sqlM = 'SELECT DID FROM `device_tb` order by DID desc';
    $rsM = $mDB->prepare($sqlM);
    $rsM->execute();
    $last_num = 0;
    if ($rsM->rowCount() != 0) {
        list($DID_r) = $rsM->fetch(PDO::FETCH_NUM);
        $last_num = substr($DID_r, -4);
    }
    $new_DID = sprintf("MDMS.D%04d", $last_num += 1);
    return $new_DID;
}

?>