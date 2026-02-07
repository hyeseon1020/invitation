<?php
include_once("../config/config.php");

$data = json_decode(file_get_contents("php://input"), true);
foreach ($data as $row) {
  $stmt = $conn->prepare(
    "UPDATE tb_invite_photo SET sort_order=? , updated_at = NOW() WHERE photo_id=?"
  );
  $stmt->bind_param("ii", $row['order'], $row['id']);
  $stmt->execute();
}
