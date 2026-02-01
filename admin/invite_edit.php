<?php
include_once("../config/config.php");

if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php"); exit;
}

$id = $_GET['id'] ?? '';
$data = [
  'invite_category'=>'','category_no'=>'','name1'=>'','name2'=>'','party_date'=>'',
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
<style>
  .photo-list { display:flex; gap:10px; flex-wrap:wrap; }
  .photo-item { cursor:move; text-align:center; }
  .photo-item img { width:120px; }
</style>
<body>
<h2>초대장 <?= $id?'수정':'등록' ?></h2>

<form method="post" action="invite_save.php">
<input type="hidden" name="invite_id" value="<?= $id ?>">
<label for="invite_category">카테고리:</label>
<select name="invite_category" id="invite_category" required>
    <option value="">-- 선택하세요 --</option>
    <option value="wedding" <?= ($data['invite_category'] == 'wedding') ? 'selected' : '' ?>>결혼</option>
    <option value="birthday" <?= ($data['invite_category'] == 'birthday') ? 'selected' : '' ?>>생일</option>
</select>

<label for="invite_theme">테마:</label>
<select name="invite_theme">
  <option value="">-- 카테고리 먼저 선택하세요 --</option>
  <option value="theme_basic" <?php if($data['invite_theme'] == 'theme_basic') echo 'selected'; ?>>기본</option>
  <option value="theme_romantic"<?php if($data['invite_theme'] == 'theme_romantic') echo 'selected'; ?>>로맨틱</option>
  <option value="theme_dark" <?php if($data['invite_theme'] == 'theme_dark') echo 'selected'; ?>>다크</option>
</select>
<input name="name1" placeholder="신랑 이름" value="<?= $data['name1'] ?>" required>
<input name="name2" placeholder="신부 이름" value="<?= $data['name2'] ?>" required>
<input type="date" name="party_date" value="<?= $data['party_date'] ?>" required>
<input type="time" name="party_time" value="<?= $data['party_time'] ?>" required>
<input name="hall_name" placeholder="예식장" value="<?= $data['hall_name'] ?>">
<input name="hall_address" placeholder="주소" value="<?= $data['hall_address'] ?>">
<input name="title" placeholder="제목" value="<?= $data['title'] ?>">
<textarea name="contents" placeholder="인사말"><?= $data['contents'] ?></textarea>
<button type="submit">저장</button>
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
