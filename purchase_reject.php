<?php
session_start();

include("db.php");

$requestcno = $_GET['requestcno'];
$cno = $_GET['cno'];
$itemno = $_GET['itemno'];

try {

    /* =========================
       1. DELETE REQUEST (FIXED TABLE NAME)
       ========================= */
    $sql = "
    DELETE FROM PURCHASEREQ
    WHERE REQUESTCNO = :requestcno
    AND CNO = :cno
    AND ITEMNO = :itemno
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':requestcno', $requestcno);
    $stmt->bindParam(':cno', $cno);
    $stmt->bindParam(':itemno', $itemno);

    $stmt->execute();


    /* =========================
       2. RESTORE ITEM STATUS
       ========================= */
    $sql2 = "
    UPDATE ITEM
    SET SELLSTATUS = '판매 중'
    WHERE ITEMNO = :itemno
    ";

    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindParam(':itemno', $itemno);
    $stmt2->execute();


    /* =========================
       3. SUCCESS RESPONSE
       ========================= */
    echo "<script>
        alert('구매 요청 거절 완료 - 상품이 다시 판매중으로 변경되었습니다.');
        location.href='purchase_request_list.php';
    </script>";

}
catch(PDOException $e)
{
    echo "<script>
        alert('시스템 오류: 처리 실패');
        history.back();
    </script>";
}
?>