<?php
include_once("../config/config.php");

$id = $_POST['invite_id'] ?? '';
$name2 = $_POST['name2'] ?? '';
$name3 = $_POST['name3'] ?? '';
$name4 = $_POST['name4'] ?? '';
$name5 = $_POST['name5'] ?? '';
$name6 = $_POST['name6'] ?? '';
$invite_code = substr(md5(uniqid()),0,8);

if ($id) {
  $sql = "UPDATE tb_invitation SET invite_category=?, invite_theme=?, name1=?, name2=?, name3=?, name4=?, name5=?, name6=?, party_date=?, party_time=?, hall_name=?, hall_address=?, title=?, contents=?, updated_at = NOW(), updated_id = ?  WHERE invite_id=?";
  $stmt = $conn->prepare($sql);
  var_dump($sql);
  $stmt->bind_param(
    "ssssssssssssssii",
    $_POST['invite_category'],$_POST['invite_theme'],$_POST['name1'],$_POST['name2'],$_POST['name3'],$_POST['name4'],$_POST['name5'],$_POST['name6'],
    $_POST['party_date'],$_POST['party_time'],$_POST['hall_name'],$_POST['hall_address'],
    $_POST['title'],$_POST['contents'],$_SESSION['admin_id'],$id
  );
} else {
  $sql = "INSERT INTO tb_invitation (invite_code,invite_category,invite_theme,name1,name2,name3,name4,name5,name6,party_date,party_time,hall_name,hall_address,title,contents,created_id)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssssssss",
    $invite_code,$_POST['invite_category'],$_POST['invite_theme'],$_POST['name1'],$_POST['name2'],$_POST['name3'],$_POST['name4'],$_POST['name5'],$_POST['name6'],
    $_POST['party_date'],$_POST['party_time'],$_POST['hall_name'],$_POST['hall_address'],
    $_POST['title'],$_POST['contents'], $_SESSION['admin_id']
  );
}

$stmt->execute();
// header("Location: invite_list.php");
header("Location: invite_edit.php?id=".$id);
