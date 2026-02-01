<?php
include_once("../config/config.php");

// 로그인 체크
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}

$sql = "SELECT * FROM tb_invitation ORDER BY invite_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>초대장 관리</title>
<style>
body {
  font-family: Arial, sans-serif;
  background: #f5f5f5;
}
.container {
  max-width: 900px;
  margin: 40px auto;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
}
table {
  width: 100%;
  border-collapse: collapse;
}
th, td {
  padding: 10px;
  border-bottom: 1px solid #ddd;
  text-align: center;
}
.top {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}
a.btn {
  padding: 8px 12px;
  background: #333;
  color: #fff;
  text-decoration: none;
  border-radius: 4px;
}
</style>
</head>
<body>

<div class="container">
  <div class="top">
    <h2>청첩장 목록</h2>
    <div>
      <a href="invite_edit.php" class="btn">+ 청첩장 등록</a>
      <a href="logout.php" class="btn">로그아웃</a>
    </div>
  </div>

  <table>
    <tr>
      <th>ID</th>
      <th>신랑 / 신부</th>
      <th>예식일</th>
      <th>URL</th>
      <th>관리</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
      <td><?= $row['invite_id'] ?></td>
      <td><?= $row['name1'] ?> & <?= $row['name2'] ?></td>
      <td><?= $row['party_date'] ?></td>
      <td>
        <a href="../public/invitation.php?code=<?= $row['invite_code'] ?>" target="_blank">
          보기
        </a>
      </td>
      <td>
        <a href="invite_edit.php?id=<?= $row['invite_id'] ?>">수정</a> |
        <a href="rsvp_list.php?id=<?= $row['invite_id'] ?>">참석자</a>
      </td>
    </tr>
    <?php } ?>
  </table>
</div>

</body>
</html>
