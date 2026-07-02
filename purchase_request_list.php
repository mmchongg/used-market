<?php
session_start();

if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}

include("db.php");

$cno = $_SESSION['cno'];

$sql = "
SELECT
    P.*,
    I.PIC1
FROM PURCHASEREQ P
JOIN ITEM I
    ON P.ITEMNO = I.ITEMNO
WHERE P.CNO = :cno
ORDER BY P.REQDATETIME DESC
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':cno', $cno);

$stmt->execute();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>구매 요청 목록</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6fb;
}

/* HEADER */

.header{
    background:#1e88e5;
    color:white;
    padding:14px 20px;
    font-size:18px;
    font-weight:600;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

.home-btn{
    background:white;
    color:#1e88e5;
    text-decoration:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
}

.home-btn:hover{
    background:#f1f1f1;
}

/* WRAPPER */
.wrapper{
    max-width:1000px;
    margin:20px auto;
}

/* CARD */
.card{
    background:white;
    border-radius:16px;
    padding:16px;
    margin-bottom:16px;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    display:flex;
    gap:16px;
}

/* IMAGE */
.img-box{
    width:140px;
    height:140px;
    border-radius:12px;
    overflow:hidden;
    flex-shrink:0;
}

.img-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* INFO */
.info{
    flex:1;
}

.title{
    font-size:16px;
    font-weight:700;
    margin-bottom:6px;
}

.price{
    color:#e74c3c;
    font-size:18px;
    font-weight:800;
    margin-bottom:10px;
}

/* GRID INFO */
.grid{
    display:grid;
    grid-template-columns:100px 1fr;
    row-gap:6px;
    font-size:13px;
}

.label{
    color:#888;
}

/* BUTTONS */
.actions{
    margin-top:12px;
    display:flex;
    gap:10px;
}

.btn{
    padding:8px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
}

.accept{
    background:#2ecc71;
    color:white;
}

.reject{
    background:#e74c3c;
    color:white;
}
</style>
</head>

<body>

<div class="header">
구매 요청 목록
    <a href="main.php" class="home-btn">
        홈으로
    </a>
</div>

<div class="wrapper">

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>

<div class="card">

    <!-- PRODUCT IMAGE -->
    <div class="img-box">
        <img src="uploads/<?php echo $row['PIC1']; ?>" 
             onerror="this.src='https://via.placeholder.com/150';">
    </div>

    <!-- INFO -->
    <div class="info">

        <div class="title">
            상품번호 #<?php echo $row['ITEMNO']; ?>
        </div>

        <div class="price">
            ₩<?php echo number_format($row['REQPRICE']); ?>
        </div>

        <div class="grid">

            <div class="label">구매자</div>
            <div><?php echo $row['REQUESTCNO']; ?></div>

            <div class="label">메시지</div>
            <div><?php echo $row['REQMESSAGE']; ?></div>

            <div class="label">요청시간</div>
            <div><?php echo $row['REQDATETIME']; ?></div>

        </div>

        <!-- ACTIONS -->
        <div class="actions">

            <a class="btn accept"
               href="purchase_accept.php?requestcno=<?php echo $row['REQUESTCNO']; ?>&cno=<?php echo $row['CNO']; ?>&itemno=<?php echo $row['ITEMNO']; ?>">
               승인
            </a>

            <a class="btn reject"
               href="purchase_reject.php?requestcno=<?php echo $row['REQUESTCNO']; ?>&cno=<?php echo $row['CNO']; ?>&itemno=<?php echo $row['ITEMNO']; ?>">
               거절
            </a>

        </div>

    </div>

</div>

<?php } ?>

</div>

</body>
</html>