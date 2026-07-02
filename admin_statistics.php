<?php
session_start();

//로그인 여부 확인
if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}
//관리자만 접근 가능
if($_SESSION['cno'] != 'c0')
{
    echo "관리자만 접근 가능합니다.";
    exit();
}

include("db.php");
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>관리자 통계</title>

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
    font-weight:600;
}

/* CONTAINER */
.container{
    max-width:1100px;
    margin:30px auto;
    padding:20px;
}

/* CARDS */
.grid{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
}

.card{
    background:white;
    padding:18px;
    border-radius:14px;
    box-shadow:0 3px 10px rgba(0,0,0,0.06);
}

.card h3{
    margin:0;
    font-size:14px;
    color:#666;
}

.card .num{
    font-size:26px;
    font-weight:bold;
    margin-top:8px;
    color:#1e88e5;
}

/* SECTION */
.section{
    background:white;
    margin-top:20px;
    padding:20px;
    border-radius:14px;
    box-shadow:0 3px 10px rgba(0,0,0,0.06);
}

.section h2{
    margin-top:0;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:left;
}

th{
    background:#f8f9fc;
}

/* BACK */
.back{
    display:inline-block;
    margin-top:20px;
    color:#666;
    text-decoration:none;
}

.back:hover{
    color:#000;
}
</style>
</head>

<body>

<div class="header">
관리자 통계 대시보드
</div>

<div class="container">

<!-- TOP STATS -->
<div class="grid">

<?php
//전체 회원 수 조회
$sql = "SELECT COUNT(*) CNT FROM CUSTOMER";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="card">
<h3>전체 회원</h3>
<div class="num"><?= $row['CNT'] ?>명</div>
</div>

<?php
//등록된 전체 상품 수 조회
$sql = "SELECT COUNT(*) CNT FROM ITEM";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="card">
<h3>전체 상품</h3>
<div class="num"><?= $row['CNT'] ?>개</div>
</div>

<?php
//채팅방 수 조회
$sql = "SELECT COUNT(*) CNT FROM CHATROOM";
$stmt = $conn->prepare($sql);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<div class="card">
<h3>채팅방</h3>
<div class="num"><?= $row['CNT'] ?>개</div>
</div>

</div>

<!-- CATEGORY -->
<div class="section">
<h2>카테고리별 상품 수</h2>

<?php
$sql = "
SELECT CATEGORY, COUNT(*) CNT
FROM ITEM
GROUP BY CATEGORY
ORDER BY CNT DESC
";

$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<table>
<tr>
<th>카테고리</th>
<th>개수</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
<tr>
<td><?= $row['CATEGORY'] ?></td>
<td><?= $row['CNT'] ?>개</td>
</tr>
<?php } ?>
</table>
</div>

<!-- RANKING -->
<div class="section">
<h2>회원별 거래액 순위</h2>

<?php
//거래 완료된 상품을 기준으로 
//회원별 총 거래 금액 및 순위 계산
$sql = "
SELECT
    CNO,
    SUM(NVL(FINALPRICE, PRICE)) TOTALPRICE,
    RANK() OVER(
        ORDER BY SUM(NVL(FINALPRICE, PRICE)) DESC
    ) RANKING
FROM ITEM
WHERE SELLSTATUS = '거래 완료'
GROUP BY CNO
";

$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<table>
<tr>
<th>순위</th>
<th>회원</th>
<th>거래액</th>
</tr>

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
<tr>
<td><?= $row['RANKING'] ?></td>
<td><?= $row['CNO'] ?></td>
<td><?= number_format($row['TOTALPRICE']) ?>원</td>
</tr>
<?php } ?>
</table>
</div>

<a class="back" href="admin.php">← 관리자 페이지로</a>

</div>

</body>
</html>