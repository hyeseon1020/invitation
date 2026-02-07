<?php
include_once("../config/config.php");

$id = (int)$_GET['id'];
$invite_id = (int)$_GET['invite_id'];

$conn->query("UPDATE tb_invite_photo SET is_main=0 WHERE invite_id=$invite_id");
$conn->query("UPDATE tb_invite_photo SET is_main=1 WHERE photo_id=$id");

header("Location: invite_edit.php?id=".$invite_id);
exit;
