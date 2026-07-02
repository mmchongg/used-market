<?php
session_start();

include("db.php");

if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}

$cno = $_SESSION['cno'];

$title = $_POST['title'];
$price = $_POST['price'];
$category = $_POST['category'];
$description = $_POST['description'];
$tradeplace = $_POST['tradeplace'];
$pic1 = "";
$pic2 = "";
$pic3 = "";

if(isset($_FILES['pic1']) && $_FILES['pic1']['size'] > 0)
{
    $pic1 = time()."_1_".$_FILES['pic1']['name'];

    move_uploaded_file(
        $_FILES['pic1']['tmp_name'],
        "uploads/".$pic1
    );


}

if(isset($_FILES['pic2']) && $_FILES['pic2']['size'] > 0)
{
    $pic2 = time()."_2_".$_FILES['pic2']['name'];

    move_uploaded_file(
        $_FILES['pic2']['tmp_name'],
        "uploads/".$pic2
    );
}

if(isset($_FILES['pic3']) && $_FILES['pic3']['size'] > 0)
{
    $pic3 = time()."_3_".$_FILES['pic3']['name'];

    move_uploaded_file(
        $_FILES['pic3']['tmp_name'],
        "uploads/".$pic3
    );
}

/*
판매자별 ITEMNO 생성
예)
A001 : 1,2,3
A002 : 1,2
*/

$sql = "
SELECT NVL(MAX(ITEMNO), 0) + 1 AS NEXTNO
FROM ITEM
WHERE CNO = :cno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':cno', $cno);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$itemno = $row['NEXTNO'];


/*
상품 등록
*/

$sql = "
INSERT INTO ITEM
(
    CNO,
    ITEMNO,
    TITLE,
    DESCRIPTION,
    CATEGORY,
    PRICE,
    TRADEPLACE,
    REGDATETIME,
    SELLSTATUS,
    PIC1,
    PIC2,
    PIC3
)
VALUES
(
    :cno,
    :itemno,
    :title,
    :description,
    :category,
    :price,
    :tradeplace,
    SYSTIMESTAMP,
    '판매 중',
    :pic1,
    :pic2,
    :pic3
)
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':itemno', $itemno);
$stmt->bindParam(':title', $title);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':category', $category);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':tradeplace', $tradeplace);
$stmt->bindParam(':pic1', $pic1);
$stmt->bindParam(':pic2', $pic2);
$stmt->bindParam(':pic3', $pic3);

$result = $stmt->execute();

if($result)
{
    echo "<h2>상품 등록 성공</h2>";

    echo "상품번호 : ".$itemno."<br><br>";

    echo "<a href='item_insert.php'>계속 등록</a><br><br>";

    echo "<a href='main.php'>메인으로</a>";
}
else
{
    echo "<h2>상품 등록 실패</h2>";
}
?>