<?php
session_start();

if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}

include("db.php");

$cno = $_GET['cno'];
$itemno = $_GET['itemno'];

$sql = "
UPDATE ITEM
SET SELLSTATUS = '거래 완료'
WHERE CNO = :cno
AND ITEMNO = :itemno
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':itemno', $itemno);

$stmt->execute();

echo "<h2>거래 완료 처리되었습니다.</h2>";

echo "<br>";

echo "<a href='item_view.php?cno=".$cno.
"&itemno=".$itemno."'>상품으로 돌아가기</a>";
?>