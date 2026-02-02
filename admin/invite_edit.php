<?php
include_once("../config/config.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php"); exit;
}

$id = $_GET['id'] ?? '';
$data = [
  'invite_category'=>'','category_no'=>'','name1'=>'','name2'=>'','name3'=>'','name4'=>'','name5'=>'','name6'=>'','party_date'=>'',
  'party_time'=>'','hall_name'=>'','hall_address'=>'','title'=>'','contents'=>''
];

if ($id) {
  $stmt = $conn->prepare("SELECT * FROM tb_invitation WHERE invite_id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  $data = $stmt->get_result()->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="ko">
<head><meta charset="UTF-8"><title>초대장 <?= $id?'수정':'등록' ?></title></head>
<link rel="stylesheet" href="../assets/css/admin.css">

<body>
  <div class="container">
    <div class="top">
        <h2>초대장 <?= $id?'수정':'등록' ?></h2>
      <div>
        <a href="invite_list.php" class="btn">이전</a>
      </div>
    </div>

    <form method="post" action="invite_save.php" class="form-box">
      <input type="hidden" name="invite_id" value="<?= $id ?>">

      <div class="form-row">
        <label>카테고리</label>
        <select name="invite_category" required>
          <option value="">선택</option>
          <option value="wedding" <?= $data['invite_category']=='wedding'?'selected':'' ?>>결혼</option>
          <option value="birthday" <?= $data['invite_category']=='birthday'?'selected':'' ?>>생일</option>
        </select>
      </div>

      <div class="form-row">
        <label>테마</label>
        <select name="invite_theme">
          <option value="">선택</option>
          <option value="theme_basic" <?= $data['invite_theme']=='theme_basic'?'selected':'' ?>>기본</option>
          <option value="theme_romantic" <?= $data['invite_theme']=='theme_romantic'?'selected':'' ?>>로맨틱</option>
          <option value="theme_dark" <?= $data['invite_theme']=='theme_dark'?'selected':'' ?>>다크</option>
        </select>
      </div>

      <div class="form-row">
        <label>신랑 이름</label>
        <input name="name1" value="<?= $data['name1'] ?>" required>
      </div>

      <div class="form-row">
        <label>신랑 아버지 이름</label>
        <input name="name2" value="<?= $data['name2'] ?>">
      </div>

      <div class="form-row">
        <label>신랑 어머니 이름</label>
        <input name="name3" value="<?= $data['name3'] ?>">
      </div>

      <div class="form-row">
        <label>신부 이름</label>
        <input name="name4" value="<?= $data['name4'] ?>">
      </div>

      <div class="form-row">
        <label>신부 아버지 이름</label>
        <input name="name5" value="<?= $data['name5'] ?>">
      </div>

      <div class="form-row">
        <label>신부 어머니 이름</label>
        <input name="name6" value="<?= $data['name6'] ?>">
      </div>

      <div class="form-row">
        <label>날짜</label>
        <input type="date" name="party_date" value="<?= $data['party_date'] ?>" required>
      </div>

      <div class="form-row">
        <label>시간</label>
        <input type="time" name="party_time" value="<?= $data['party_time'] ?>" required>
      </div>

      <div class="form-row full">
        <label>예식장</label>
        <input name="hall_name" value="<?= $data['hall_name'] ?>">
      </div>

      <div class="form-row full">
        <label>주소</label>
        <input name="hall_address" value="<?= $data['hall_address'] ?>">
      </div>

      <div class="form-row full">
        <label>제목</label>
        <input name="title" value="<?= $data['title'] ?>">
      </div>

      <div class="form-row full">
        <label>인사말</label>
        <textarea name="contents"><?= $data['contents'] ?></textarea>
      </div>

      <button class="btn-save">저장</button>
    </form>

    <?php if ($id): ?>
    <hr>
    <h3>📸 초대장 사진 관리</h3>
  
    <!-- 사진 업로드 -->
    <form action="invite_photo_upload.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="invite_id" value="<?= $id ?>">
        <input type="file" name="photos[]" multiple accept="image/*" required>
        <button type="submit">사진 업로드</button>
    </form>
  
    <!-- 등록된 사진 목록 -->
    <div style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">
      <?php
      $photos = $conn->query("
          SELECT * FROM tb_invite_photo 
          WHERE invite_id={$id}
          ORDER BY sort_order, photo_id
      ");
  
      while ($p = $photos->fetch_assoc()):
      ?>
          <div style="text-align:center;">
              <img src="<?= $p['photo_path'] ?>" style="width:120px;height:auto;display:block;">
              <a href="invite_photo_delete.php?id=<?= $p['photo_id'] ?>&invite_id=<?= $id ?>"
              onclick="return confirm('삭제할까요?')">
              삭제
              </a>
          </div>
      <?php endwhile; ?>
    </div>
  
    <?php else: ?>
    <p style="color:#888;">
      ※ 초대장을 먼저 저장하면 사진을 추가할 수 있습니다.
    </p>
    <?php endif; ?>
  </div>


  <script>
    let dragItem = null;

    document.querySelectorAll('.photo-item').forEach(item => {
      item.addEventListener('dragstart', e => dragItem = item);
      item.addEventListener('dragover', e => e.preventDefault());
      item.addEventListener('drop', e => {
        e.preventDefault();
        if (dragItem !== item) {
          item.before(dragItem);
          saveOrder();
        }
      });
    });

    function saveOrder() {
      let ids = [...document.querySelectorAll('.photo-item')]
        .map((el, i) => ({ id: el.dataset.id, order: i }));

      fetch('invite_photo_sort.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify(ids)
      });
    }
  </script>
</body>
</html>
