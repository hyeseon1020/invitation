<?php
// checklist.php
// 1인 개발용 간단 체크리스트 관리 페이지 (MySQL / tb_ prefix 사용)

include_once("./config/config.php"); // 기존 프로젝트 DB 연결 사용

// ---------------------------
// 체크 추가
// ---------------------------
if(isset($_POST['action']) && $_POST['action'] === 'add'){
    $title = trim($_POST['title']);

    if($title !== ''){
        $stmt = $conn->prepare("INSERT INTO tb_checklist (title, url, is_done, created_at) VALUES (?, ?, 0, NOW())");
        $url = trim($_POST['url']);
        $stmt->bind_param("ss", $title, $url);
        $stmt->execute();
    }
    header("Location: checklist.php");
    exit;
}

// ---------------------------
// 체크 상태 변경
// ---------------------------
if(isset($_GET['toggle'])){
    $id = intval($_GET['toggle']);
    $conn->query("UPDATE tb_checklist SET is_done = IF(is_done=1,0,1) WHERE id = {$id}");
    header("Location: checklist.php");
    exit;
}

// ---------------------------
// 삭제
// ---------------------------
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM tb_checklist WHERE id = {$id}");
    header("Location: checklist.php");
    exit;
}

$result = $conn->query("SELECT * FROM tb_checklist ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>프로젝트 체크리스트</title>
<style>
body{font-family:Arial; background:#f4f4f4; padding:30px;}
.container{max-width:800px; margin:auto; background:#fff; padding:20px; border-radius:10px;}
h1{margin-bottom:20px;}
form{display:flex; gap:10px; margin-bottom:20px;}
input[type=text]{flex:1; padding:10px;}
button{padding:10px 15px; cursor:pointer;}
ul{list-style:none; padding:0;}
li{padding:0; border-bottom:1px solid #eee;}
.item-row{display:flex; justify-content:space-between; align-items:center;}
.item-left{flex:1; padding:15px; cursor:pointer; display:flex; align-items:center; gap:10px;}
.item-left:hover{background:#f9f9f9;}
.done{text-decoration:line-through; color:gray;}
.actions{padding-right:15px;}
.actions a{margin-left:10px; font-size:12px;}
</style>
</head>
<body>
<div class="container">
<h1>📌 PHP 프로젝트 체크리스트</h1>

<form method="POST">
    <input type="hidden" name="action" value="add">
    <input type="text" name="title" placeholder="예: 청첩장 디자인 작업" required>
    <input type="text" name="url" placeholder="이동할 경로 (예: /admin/invite_list.php)">
    <button type="submit">추가</button>
</form>

<ul>
<?php while($row = $result->fetch_assoc()): ?>
<li>
    <div class="item-row">
        <a href="?toggle=<?=$row['id']?>" class="item-left">
            <input type="checkbox" <?= $row['is_done'] ? 'checked' : '' ?> onclick="return false;">
            <div>
                <span class="<?= $row['is_done'] ? 'done' : '' ?>">
                    <?= htmlspecialchars($row['title']) ?>
                </span>
                <?php if(!empty($row['url'])): ?>
                    <!--<div style="font-size:12px; color:#888; margin-top:4px;">
                        경로: <?= htmlspecialchars($row['url']) ?>
                    </div>-->
                <?php endif; ?>
            </div>
        </a>
        <div class="actions">
            <?php if(!empty($row['url'])): ?>
                <a href="<?= htmlspecialchars($row['url']) ?>" target="_blank">이동</a>
            <?php endif; ?>
            <a href="?delete=<?=$row['id']?>" onclick="return confirm('삭제하시겠습니까?')">삭제</a>
        </div>
    </div>
</li>
<?php endwhile; ?>
</ul>

</div>
</body>
</html>
