<?php
include_once("../config/config.php");

$id = (int)$_GET['id'];
$invite_id = (int)$_GET['invite_id'];

$r = $conn->query("SELECT photo_path FROM tb_invite_photo WHERE photo_id=$id");
$row = $r->fetch_assoc();

if ($row) {
    $file = $_SERVER['DOCUMENT_ROOT'].$row['photo_path'];
    if (is_file($file)) unlink($file);

    $conn->query("DELETE FROM tb_invite_photo WHERE photo_id=$id");
}

header("Location: invite_edit.php?id=".$invite_id);
exit;
