<?php
session_start();

//로그인 하지 않은 경우 로그인 페이지로 이동
if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}

//관리자 계정(c0)이 아니면 접근 차단
if($_SESSION['cno'] != 'c0')
{
    echo "관리자만 접근 가능합니다.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>관리자 페이지</title>
</head>

<body>

<h2>관리자 페이지</h2>
<!--통계페이지로 이동 -->

<a href="admin_statistics.php">
통계 보기
</a>

<br><br>

<!--메인페이지로 이동 -->
<a href="main.php">
메인으로
</a>

</body>
</html>