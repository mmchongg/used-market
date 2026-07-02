<?php
session_start();

include("db.php");

$requestCno = $_SESSION['cno'];

$cno = $_POST['cno'];
$itemno = $_POST['itemno'];

$reqprice = $_POST['reqprice'];
$reqmessage = $_POST['reqmessage'];

$sql = "
INSERT INTO PURCHASEREQ
(
    REQUESTCNO,
    CNO,
    ITEMNO,
    REQDATETIME,
    REQPRICE,
    REQMESSAGE
)
VALUES
(
    :requestCno,
    :cno,
    :itemno,
    SYSTIMESTAMP,
    :reqprice,
    :reqmessage
)
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':requestCno',$requestCno);
$stmt->bindParam(':cno',$cno);
$stmt->bindParam(':itemno',$itemno);
$stmt->bindParam(':reqprice',$reqprice);
$stmt->bindParam(':reqmessage',$reqmessage);

$stmt->execute();

echo "<h2>구매 요청 완료</h2>";

echo "<a href='item_view.php?cno=".$cno.
"&itemno=".$itemno."'>상품으로 돌아가기</a>";
?>