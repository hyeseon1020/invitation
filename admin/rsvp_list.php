<?php
$pageTitle = "참석자 관리";
include_once("top.php");

$id = (int)$_GET['id'];

$list = $conn->query("
  SELECT * FROM tb_rsvp 
  WHERE invite_id=$id 
  ORDER BY rsvp_id DESC
");

$stat = $conn->query("
  SELECT 
    SUM(attend_yn='Y') AS yes_cnt,
    SUM(attend_yn='N') AS no_cnt
  FROM tb_rsvp 
  WHERE invite_id=$id
")->fetch_assoc();
?>

<div class="container">

  <div class="top">
    <h2>참석자 목록</h2>
    <div>
      <a href="invite_list.php" class="btn">이전</a>
    </div>
  </div>

  <!-- 통계 -->
  <div class="stat-box">
    <span>✅ 참석 <b><?= $stat['yes_cnt'] ?? 0 ?></b></span>
    <span>❌ 불참 <b><?= $stat['no_cnt'] ?? 0 ?></b></span>
  </div>

  <!-- 리스트 -->
  <table>
    <tr>
      <th>이름</th>
      <th>참석여부</th>
      <th>인원</th>
      <th>메모</th>
    </tr>

    <?php if ($list->num_rows == 0): ?>
      <tr>
        <td colspan="4" style="color:#888;">등록된 참석자가 없습니다.</td>
      </tr>
    <?php endif; ?>

    <?php while($r = $list->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($r['guest_name']) ?></td>
      <td>
        <?= $r['attend_yn']=='Y' ? '✅ 참석' : '❌ 불참' ?>
      </td>
      <td><?= $r['guest_count'] ?></td>
      <td><?= nl2br(htmlspecialchars($r['memo'])) ?></td>
    </tr>
    <?php endwhile; ?>
  </table>

</div>

<?php include_once("bottom.php"); ?>