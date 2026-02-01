<?php
include_once("../config/config.php");
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$id = $_GET['id'];
$list = $conn->query("SELECT * FROM tb_rsvp WHERE invite_id=$id ORDER BY rsvp_id DESC");

$stat = $conn->query("
  SELECT 
    SUM(attend_yn='Y') AS yes_cnt,
    SUM(attend_yn='N') AS no_cnt
  FROM tb_rsvp WHERE invite_id=$id
")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>참석자</title></head>
<body>
<h2>참석자 목록</h2>
<p>참석 <?= $stat['yes_cnt'] ?> / 불참 <?= $stat['no_cnt'] ?></p>

<table border="1" cellpadding="5">
<tr><th>이름</th><th>참석</th><th>인원</th><th>메모</th></tr>
<?php while($r=$list->fetch_assoc()){ ?>
<tr>
<td><?= $r['guest_name'] ?></td>
<td><?= $r['attend_yn'] ?></td>
<td><?= $r['guest_count'] ?></td>
<td><?= $r['memo'] ?></td>
</tr>
<?php } ?>
</table>
</body>
</html>
