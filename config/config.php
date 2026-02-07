<?php
session_start();   // 세션 시작
include_once("db.php");

// 사이트 기본 설정
define("SITE_NAME", "모바일 초대장");
define("BASE_URL", "/invitation");

// 에러 표시 (개발중에만 ON)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL); // 개발 시
//error_reporting(0); //배포 시

// 세션 설정
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
