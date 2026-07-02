<?php
session_start();

session_destroy();

echo "<h2>로그아웃 되었습니다.</h2>";

echo "<a href='login.php'>로그인 화면으로</a>";
?>