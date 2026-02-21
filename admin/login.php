<?php
include_once("../config/config.php");

// 이미 로그인 되어 있으면 목록으로
if (isset($_SESSION['admin_id'])) {
  header("Location: invite_list.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>관리자 로그인</title>
<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="login-box">
  <h2>관리자 로그인</h2>

  <form method="post" action="login_proc.php">
    <input type="text" name="username" placeholder="아이디" required>
    <input type="password" name="password" placeholder="비밀번호" required>
    <button type="submit">로그인</button>
  </form>
</div>

</body>
</html>
