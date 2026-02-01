<?php
include_once("../config/config.php");

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM tb_admin_user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($admin && password_verify($password, $admin['password'])) {
  $_SESSION['admin_id'] = $admin['admin_id'];
  $_SESSION['username'] = $admin['username'];
  header("Location: invite_list.php");
  exit;
} else {
  echo "<script>alert('아이디 또는 비밀번호가 틀렸습니다.');history.back();</script>";
}
