<?php
include_once("../config/config.php");

// 로그인 체크
if (!isset($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $pageTitle ?></title>
<link rel="shortcut icon" href="favicon.ico">
<link rel="stylesheet" href="../assets/css/admin.css" >
</head>
<body>