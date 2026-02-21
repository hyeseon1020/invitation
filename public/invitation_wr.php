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
<link rel="stylesheet" href="../assets/css/<?= $invite['invite_theme'] ?>.css">
<script src="../assets/js/common.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

</head>
<style>
  body {
    padding-bottom: 110px;
  }
</style>
<body>
  <?php
  // 대표이미지
  $main = $conn->query("
    SELECT photo_path FROM tb_invite_photo
    WHERE invite_id={$invite['invite_id']} AND is_main=1
  ")->fetch_assoc();
  ?>
  <!-- 대표 이미지 -->
  <?php if($main): ?>
  <div class="hero">
    <img src="<?= $main['photo_path'] ?>">
  </div>
  <?php endif; ?>


  <!-- 제목 -->
  <section class="intro">
    <h1><?= $invite['title'] ?></h1>
  </section>


  <!-- 혼주 정보 -->
  <section class="info-box">
    <?php if($invite['name2'] || $invite['name3']): ?>
    <p><?= $invite['name2'] ?> · <?= $invite['name3'] ?>의 아들</p>
    <?php endif; ?>
    <h3>신랑 <?= $invite['name1'] ?></h3>
  </section>

  <section class="info-box">
    <?php if($invite['name5'] || $invite['name6']): ?>
    <p><?= $invite['name5'] ?> · <?= $invite['name6'] ?>의 딸</p>
    <?php endif; ?>
    <h3>신부 <?= $invite['name4'] ?></h3>
  </section>


  <!-- 예식 정보 & 날짜 -->
  <section class="card">
    <h3>Wedding Day</h3>
     <p class="date">
      <?= $invite['party_date'] ?> 
      <?= substr($invite['party_time'],0,5) ?>
    </p>
    <p><?= $invite['hall_name'] ?></p>
    <p><?= $invite['hall_address'] ?></p>
  </section>


  <!-- 인사말 -->
  <section class="card message">
    <h3>초대합니다</h3>
    <p><?= nl2br($invite['contents']) ?></p>
  </section>
  

    <!-- 갤러리 (스와이프 전용) -->
    <section class="gallery-wrap">
    <div class="swiper">
        <div class="swiper-wrapper">
        <?php
        $photos = $conn->query("
            SELECT photo_path FROM tb_invite_photo
            WHERE invite_id={$invite['invite_id']}
            ORDER BY is_main desc, sort_order, photo_id
        ");
        while ($p = $photos->fetch_assoc()):
        ?>
        <div class="swiper-slide">
            <img src="<?= $p['photo_path'] ?>">
        </div>
        <?php endwhile; ?>
        </div>
    </div>
    </section>

    <!-- RSVP -->
    <section class="card rsvp">
    <h3>참석 여부 전달</h3>
    <form method="post" action="rsvp_save.php" onsubmit="return validateRsvpForm(this)">
        <input type="hidden" name="invite_id" value="<?= $invite['invite_id'] ?>">

        <input type="text" name="guest_name" placeholder="이름" required>

        <input type="text" id="display_hp" placeholder="전화번호" required oninput="formatPhoneNumber(this)" maxlength="13">
        <input type="hidden" name="guest_hp" id="hidden_hp">

        <select name="attend_yn">
        <option value="Y">참석</option>
        <option value="N">불참</option>
        </select>

        <input type="number" name="guest_count" min="1" value="1">
        <input type="text" name="memo" placeholder="한마디">

        <button type="submit">전송하기</button>
    </form>
    </section>


    <!-- 지도 버튼 -->
    <a class="fixed-btn"
    href="https://map.kakao.com/link/search/<?= urlencode($invite['hall_name']) ?>"
    target="_blank">
    📍 예식장 위치 보기
    </a>


    <script>
    new Swiper('.swiper', {    
        // 한 화면에 보여줄 슬라이드 개수
        // 'auto'로 설정하면 CSS에서 정한 너비만큼 보이고 나머지는 옆에 걸쳐집니다.
        slidesPerView: 1.5, 

        // 슬라이드 사이의 간격 (px)
        spaceBetween: 50,

        // 활성화된 슬라이드를 가운데 배치 (옆 이미지들이 양옆으로 보임)
        centeredSlides: true,

        // 네비게이션/페이지네이션이 있다면 추가
        pagination: {
        el: '.swiper-pagination',
        clickable: true,
        },
    });
    </script>

</body>
</html>
