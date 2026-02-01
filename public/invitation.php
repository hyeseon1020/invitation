<?php
include_once("../config/config.php");

$invite_code = $_GET['code'] ?? '';
$sql = "SELECT * FROM tb_invitation WHERE invite_code = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $invite_code);
$stmt->execute();
$result = $stmt->get_result();
$invite = $result->fetch_assoc();

if (!$invite) {
  echo "존재하지 않는 초대장입니다.";
  exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>모바일 청첩장</title>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="/assets/css/<?= $invite['invite_theme'] ?>.css">
<script src="../assets/js/common.js"></script>

</head>
<body>

<div class="card">
  <h1><?= $invite['name1'] ?> ❤ <?= $invite['name2'] ?></h1>
  <p class="date">
    <?= $invite['party_date'] ?>
    <?= substr($invite['party_time'],0,5) ?>
  </p>
</div>

<div class="card">
  <h3>예식장</h3>
  <p><?= $invite['hall_name'] ?></p>
  <p><?= $invite['hall_address'] ?></p>
</div>

<div class="card">
  <h3>인사말</h3>
  <p><?= nl2br($invite['contents']) ?></p>
</div>

<div class="card">
  <h3>참석 여부</h3>
  <form method="post" action="rsvp_save.php" onsubmit="return validateRsvpForm(this)">
    <input type="hidden" name="invite_id" value="<?= $invite['invite_id'] ?>">
    <input type="text" name="guest_name" placeholder="이름" required value="">
    <input type="text" id="display_hp" placeholder="전화번호" required oninput="formatPhoneNumber(this)" maxlength="13">
    <input type="hidden" name="guest_hp" id="hidden_hp" value="">
    <select name="attend_yn" onchange="toggleGuestCount(this)">
      <option value="Y">참석</option>
      <option value="N">불참</option>
    </select>
    <input type="number" name="guest_count" min="1" value="1">
    <input type="text" name="memo" placeholder="한마디" value="">
    <button type="submit">전송</button>
  </form>
</div>

<div class="gallery">
<?php
$photos = $conn->query("SELECT * FROM tb_invite_photo WHERE invite_id={$invite['invite_id']} ORDER BY sort_order");
while ($p = $photos->fetch_assoc()) {
?>
    <img src="<?= $p['photo_path'] ?>" class="gallery-img">
<?php } ?>
</div>

<a class="fixed-btn"
   href="https://map.kakao.com/link/search/<?= urlencode($invite['hall_name']) ?>"
   target="_blank">📍 지도 보기</a>

<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=
  <?php 
    if($_SERVER['HTTP_HOST'] == 'hyesun1020.dothome.co.kr'){
      echo urlencode('hyesun1020.dothome.co.kr/invitation/public/invitation.php?code=' . $invite['invite_code']);
    }else{
      echo urlencode('localhost:8000/public/invitation.php?code=' . $invite['invite_code']); 
    }
  ?>
">


</body>
</html>
