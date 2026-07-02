<?php
session_start();

if(!isset($_SESSION['cno'])){
    header("Location: login.php");
    exit();
}

include("db.php");

$requestcno = $_GET['requestcno'];
$cno = $_GET['cno'];
$itemno = $_GET['itemno'];

/* =========================
   1. UPDATE ITEM STATUS
   ========================= */
$sql = "
UPDATE ITEM
SET SELLSTATUS = '예약 중'
WHERE CNO = :cno
AND ITEMNO = :itemno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':itemno', $itemno);
$stmt->execute();

/* =========================
   2. CHECK CHATROOM EXISTS
   ========================= */
$sql = "
SELECT COUNT(*) CNT
FROM CHATROOM
WHERE ITEMNO = :itemno
AND CNO = :cno
AND RECEIVECNO = :requestcno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':itemno', $itemno);
$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':requestcno', $requestcno);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

/* =========================
   3. CREATE CHATROOM ONLY IF NOT EXISTS
   ========================= */
if($row['CNT'] == 0){

    $sql = "
    INSERT INTO CHATROOM
    (
        receiveCno,
        createDateTime,
        cno,
        itemNo
    )
    VALUES
    (
        :requestcno,
        SYSTIMESTAMP,
        :cno,
        :itemno
    )
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':requestcno', $requestcno);
    $stmt->bindParam(':cno', $cno);
    $stmt->bindParam(':itemno', $itemno);

    $stmt->execute();
}

/* =========================
   4. SUCCESS MESSAGE
   ========================= */
echo "<h2>구매 요청 승인 완료</h2>";
echo "<br>";
echo "상품 상태가 '예약 중'으로 변경되었습니다.";
echo "<br><br>";
echo "<a href='purchase_request_list.php'>구매 요청 목록으로</a>";
?>