<?php
$pageTitle = "초대장";
include_once("top.php");

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

  <div class="container">
    <div class="top">
        <h2> <?=$pageTitle?> <?= $id?'수정':'등록' ?></h2>
      <div>
        <a href="invite_list.php" class="btn">이전</a>
      </div>
    </div>
    <div class="text-right">
      <?php if(isset($data['invite_code']) && $data['invite_code']): ?>
      <a href="/public/invitation.php?code=<?= $data['invite_code'] ?>" target="_blank">
        👀 미리보기
      </a>
      <?php endif; ?>
    </div>

    <form method="post" action="invite_save.php" class="form-box">
      <input type="hidden" name="invite_id" value="<?= $id ?>">

      <div class="form-row">
        <label>카테고리</label>
        <select name="invite_category" required>
          <option value="">선택</option>
          <option value="wedding" <?= isset($data['invite_category']) && $data['invite_category']=='wedding'?'selected':'' ?>>결혼</option>
          <option value="birthday" <?= isset($data['invite_category']) && $data['invite_category']=='birthday'?'selected':'' ?>>생일</option>
        </select>
      </div>

      <div class="form-row">
        <label>테마</label>
        <select name="invite_theme">
          <option value="">선택</option>
          <option value="theme_basic" <?= isset($data['invite_theme']) && $data['invite_theme']=='theme_basic'?'selected':'' ?>>기본</option>
          <option value="theme_romantic" <?= isset($data['invite_theme']) && $data['invite_theme']=='theme_romantic'?'selected':'' ?>>로맨틱</option>
          <option value="theme_dark" <?= isset($data['invite_theme']) && $data['invite_theme']=='theme_dark'?'selected':'' ?>>다크</option>
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
    <div class="photo-list" id="photoList" style="margin-top: 10px;">
      <?php
      $photos = $conn->query("
          SELECT * FROM tb_invite_photo 
          WHERE invite_id={$id}
          ORDER BY is_main desc, sort_order, photo_id
      ");
  
      while ($p = $photos->fetch_assoc()):
      ?>
          <div class="photo-item" draggable="true" data-id="<?= $p['photo_id'] ?>">
              <?php
                if(!$p['is_main']){
                  echo(
                    '<a href="invite_photo_main.php?id='. $p["photo_id"] . '&invite_id=' . $id .'" class="btn" style="display:block;">
              대표로 설정
              </a>'
                  );
                }
              ?>
              
              <div class="text-right" style="<?= $p['is_main'] ? 'padding-bottom: 16px;' : ''?>" >
                <small><?= $p['is_main'] ? '⭐' : '' ?></small>
              </div>
              <br>
              <img src="<?= $p['photo_path'] ?>" draggable="false">
              <a href="invite_photo_delete.php?id=<?= $p['photo_id'] ?>&invite_id=<?= $id ?>"
              onclick="return confirm('삭제할까요?')" class="btn a-delete" style="display:block;">
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
    document.querySelector('input[type=file]').addEventListener('change', e => {
      for (let f of e.target.files) {
        if (f.size > 10 * 1024 * 1024) {
          alert('사진은 10MB 이하만 업로드 가능합니다.');
          e.target.value = '';
          return;
        }
      }
    });
    
    // photo_drop_sort
    document.addEventListener('DOMContentLoaded', () => {
      let dragItem = null;

      document.querySelectorAll('.photo-item').forEach(item => {
        item.addEventListener('dragstart', () => dragItem = item);
        item.addEventListener('dragover', e => e.preventDefault());
        item.addEventListener('drop', e => {
          e.preventDefault();
          if (dragItem && dragItem !== item) {
            item.before(dragItem);
            saveOrder();
          }
        });
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

<?php include_once("bottom.php"); ?>