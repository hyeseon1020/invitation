<?php
include_once("../config/config.php");

$id = $_POST['invite_id'] ?? '';
$invite_code = substr(md5(uniqid()),0,8);
print_r($_POST['category_no']);
echo $invite_code;
echo '<pre>';

if ($id) {
  $sql = "UPDATE tb_invitation SET invite_category=?, category_no=?, name1=?, name2=?, party_date=?, party_time=?, hall_name=?, hall_address=?, title=?, contents=? WHERE invite_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssi",
    $_POST['name1'],$_POST['name2'],$_POST['party_date'],
    $_POST['party_time'],$_POST['hall_name'],$_POST['hall_address'],
    $_POST['title'],$_POST['contents'],$id
  );
} else {
  $sql = "INSERT INTO tb_invitation (invite_code,invite_category,category_no,name1,name2,party_date,party_time,hall_name,hall_address,title,contents,created_id)
          VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
          var_dump($sql);
          echo'</pre>';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "ssssssssssss",
    $invite_code,$_POST['invite_category'],$_POST['category_no'],$_POST['name1'],$_POST['name2'],$_POST['party_date'],
    $_POST['party_time'],$_POST['hall_name'],$_POST['hall_address'],
    $_POST['title'],$_POST['contents'], $_SESSION['admin_id']
  );
}

$stmt->execute();
header("Location: invite_list.php");
