<?php
session_start();
include("db.php");

// 채팅방 번호와 최종 거래 금액 전달받기
$roomno = $_POST['roomno'];
$finalprice = $_POST['finalprice'];

//채팅방 정보 조회
$sql = "
SELECT *
FROM CHATROOM
WHERE ROOMNO = :roomno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':roomno', $roomno);
$stmt->execute();

$chatroom = $stmt->fetch(PDO::FETCH_ASSOC);
//판매자 권한 확인
//판매자만 거래 완료 가능
if($chatroom['CNO'] != $_SESSION['cno']){
    exit("판매자만 거래 완료를 할 수 있습니다.");
}

//거래 상품 번호 조회
$itemno = $chatroom['ITEMNO'];

//상품 상태를 거래 완료로 변경
//최종 거래 금액 저장
$sql = "
UPDATE ITEM
SET SELLSTATUS='거래 완료',
    FINALPRICE=:finalprice
WHERE ITEMNO=:itemno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':finalprice', $finalprice);
$stmt->bindParam(':itemno', $itemno);
$stmt->execute();

//거래 완료 후 채팅방으로 이동
header("Location: chat.php?roomno=".$roomno);
exit();
?>