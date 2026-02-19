<?php
$pageTitle = "관리자 로그인";
include_once("top.php");
?>

<div class="login-box">
  <h2>관리자 로그인</h2>

  <form method="post" action="login_proc.php">
    <input type="text" name="username" placeholder="아이디" required>
    <input type="password" name="password" placeholder="비밀번호" required>
    <button type="submit">로그인</button>
  </form>
</div>

<?php include_once("bottom.php"); ?>