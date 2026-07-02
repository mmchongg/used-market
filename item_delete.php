<?php
session_start();

//로그인 여부 확인
if(!isset($_SESSION['cno'])){
    header("Location: login.php");
    exit();
}
// DB연결
include("db.php");

//삭제할 상품 번호
$itemno = $_GET['itemno'];

//현재 로그인한 사용자의 상품만 삭제
$sql = "
DELETE FROM ITEM
WHERE ITEMNO = :itemno
AND CNO = :cno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':itemno', $itemno);
$stmt->bindParam(':cno', $_SESSION['cno']);
$stmt->execute();

//삭제 후 메인 페이지로 이동
header("Location: main.php");
exit();
?>