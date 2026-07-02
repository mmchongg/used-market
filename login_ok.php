<?php
session_start();

include("db.php");

$cno = $_POST['cno'];
$passwd = $_POST['passwd'];

$sql = "
SELECT *
FROM CUSTOMER
WHERE CNO = :cno
AND PASSWD = :passwd
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':passwd', $passwd);

$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user)
{
    $_SESSION['cno'] = $user['CNO'];
    $_SESSION['nickname'] = $user['NICKNAME'];

    echo "
    <script>
        alert('로그인 성공');
        window.location.href = 'main.php';
    </script>
    ";
    exit;
}
else
{
    echo "
<script>
alert('아이디 또는 비밀번호가 틀렸습니다.');
history.back();
</script>
";
exit;

  //  echo "<h2>로그인 실패</h2>";
 //   echo "아이디 또는 비밀번호가 틀렸습니다.<br><br>";

  //  echo "<a href='login.php'>다시 로그인</a>";
}
?>