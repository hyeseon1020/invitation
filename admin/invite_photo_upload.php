<?php
include_once("../config/config.php");

$invite_id = (int)$_POST['invite_id'];

if($_SERVER['HTTP_HOST'] == 'hyesun1020.dothome.co.kr'){
    $upload_dir = $_SERVER['DOCUMENT_ROOT']."/invitation/uploads/gallery/";
}else{
    $upload_dir = $_SERVER['DOCUMENT_ROOT']."/uploads/gallery/";
}

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}


file_put_contents(
  __DIR__ . '/photo_debug.txt',
  date('Y-m-d H:i:s') . " PHP 실행됨\n" .
  "DECODE: " . print_r($_FILES, true) . "\n",
  FILE_APPEND
);

$sort = 0;
foreach ($_FILES['photos']['tmp_name'] as $i => $tmp) {
    if ($_FILES['photos']['error'][$i] === UPLOAD_ERR_INI_SIZE) {
        echo 'php.ini 용량 초과';
    }

    if ($_FILES['photos']['error'][$i] !== 0) continue;

    $ext = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
    if (!in_array($ext, ['jpg','jpeg','png','gif','webp','heic'])) {
        continue;
    }

    $filename = uniqid('photo_') . '.' . $ext;
    $target = $upload_dir . $filename;

    if (move_uploaded_file($tmp, $target)) {

        if($_SERVER['HTTP_HOST'] == 'hyesun1020.dothome.co.kr'){
            $path = "/invitation/uploads/gallery/" . $filename;
        }else{
            $path = "/uploads/gallery/" . $filename;
        }

        $stmt = $conn->prepare(
            "INSERT INTO tb_invite_photo (invite_id, photo_path, sort_order)
             VALUES (?, ?, ?)"
        );

        if (!$stmt) {
            die($conn->error);
        }

        $stmt->bind_param("isi", $invite_id, $path, $sort);
        $stmt->execute();
        $sort++;
        print_r($sort);
    }
}

header("Location: invite_edit.php?id=".$invite_id);
exit;
