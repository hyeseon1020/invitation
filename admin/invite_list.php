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
<link rel="stylesheet" href="../assets/css/admin.css">
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
      <td><?= $row['name1'] ?> & <?= $row['name4'] ?></td>
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
