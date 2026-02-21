<?php
include_once("../config/config.php");

$invite_id   = $_POST['invite_id'];
$guest_name  = $_POST['guest_name'];
$guest_hp   = $_POST['guest_hp'];
$attend_yn   = $_POST['attend_yn'];
$guest_count = $_POST['guest_count'];
$memo        = $_POST['memo'];
echo $guest_name;
$sql = " SELECT * FROM tb_rsvp WHERE invite_id=? and guest_hp=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is",$invite_id,$guest_hp);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
var_dump($data) ;
if(!empty($data)){
  $existing_id = $data['rsvp_id'];
  echo "<script>alert('이미 참석 여부가 전달되었습니다.');history.back();</script>";
}else{
  $sql = "
  INSERT INTO tb_rsvp
  (invite_id, guest_name, guest_hp, attend_yn, guest_count, memo)
  VALUES (?, ?, ?, ?, ?, ?)
  ";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(
    "isssis",
    $invite_id,
    $guest_name,
    $guest_hp,
    $attend_yn,
    $guest_count,
    $memo
  );
  $stmt->execute();

  echo "<script>alert('참석 여부가 전달되었습니다.');history.back();</script>";
}


