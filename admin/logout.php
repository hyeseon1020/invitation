<?php
session_start();   // 세션 시작

// 세션 변수 전부 제거
$_SESSION = [];

// 세션 쿠키 삭제 (권장)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 세션 완전 파괴
session_destroy();

// 로그인 페이지로 이동
if($_SERVER['HTTP_HOST'] == 'hyesun1020.dothome.co.kr'){
    header("Location: /invitation/index.php");
}else{
    header("Location: /index.php");
}
exit;
