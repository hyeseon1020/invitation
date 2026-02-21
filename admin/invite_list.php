<?php
$pageTitle = "초대장 관리";
include_once("top.php");

$sql = "SELECT * FROM tb_invitation i LEFT JOIN tb_code c ON c.code_id = i.invite_theme ORDER BY invite_id DESC";
$result = $conn->query($sql);
?>

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
        <a href="../public/<?= $row['add1'] ?>.php?code=<?= $row['invite_code'] ?>" target="_blank">
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

<?php include_once("bottom.php"); ?>