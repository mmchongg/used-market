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

//상품 정보 조회
$sql = "
SELECT *
FROM ITEM
WHERE CNO = :cno
AND ITEMNO = :itemno
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':itemno', $itemno);
$stmt->execute();

$item = $stmt->fetch(PDO::FETCH_ASSOC);

//상품 존재 여부 확인
if(!$item)
{
    echo "존재하지 않는 상품입니다.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>상품 상세보기</title>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:'Noto Sans KR', sans-serif;
    background:#f4f6fb;
}

/* HEADER */
.header{
    background:#1e88e5;
    color:white;
    padding:14px 20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header a{
    color:white;
    text-decoration:none;
    margin-left:10px;
}

/* WRAP */
.wrap{
    max-width:1100px;
    margin:30px auto;
    display:flex;
    gap:25px;
}

/* GALLERY */
.gallery-wrap{
    display:flex;
    gap:15px;
    align-items:flex-start;
    flex:1;
}

/* THUMB LIST */
.thumb-list{
    display:flex;
    flex-direction:column;
    gap:10px;
}

.thumb-list img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    cursor:pointer;
    border:2px solid transparent;
}

.thumb-list img:hover{
    border:2px solid #1e88e5;
}

/* MAIN IMAGE */
.main-image{
    flex:1;
}

.main-image img{
    width:100%;
    height:520px;
    object-fit:cover;
    border-radius:14px;
    box-shadow:0 2px 12px rgba(0,0,0,0.1);
}

/* INFO */
.info{
    flex:1;
    background:white;
    padding:20px;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,0.06);
}

.title{
    font-size:22px;
    font-weight:700;
}

.price{
    font-size:26px;
    font-weight:800;
    color:#e53935;
    margin:15px 0;
}

.row{
    margin:10px 0;
    font-size:14px;
}

.label{
    font-size:12px;
    color:#888;
}

/* BUTTONS */
.btns{
    margin-top:20px;
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn{
    padding:10px 14px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
}

.buy{background:#1e88e5;color:white;}
.chat{background:#ff9800;color:white;}
.back{background:#eee;color:#333;}
.delete{
    background:#e53935;
    color:white;
}
</style>
</head>

<body>

<div class="header">
    <div>중고거래 사이트</div>
    <div>
        <a href="main.php">🏠 홈</a>
        <a href="logout.php">로그아웃</a>
    </div>
</div>

<div class="wrap">

<!-- IMAGE -->
<div class="gallery-wrap">

    <div class="thumb-list">

        <?php if(!empty($item['PIC1'])) { ?>
            <img src="uploads/<?php echo $item['PIC1']; ?>" onclick="setImg(this)">
        <?php } ?>

        <?php if(!empty($item['PIC2'])) { ?>
            <img src="uploads/<?php echo $item['PIC2']; ?>" onclick="setImg(this)">
        <?php } ?>

        <?php if(!empty($item['PIC3'])) { ?>
            <img src="uploads/<?php echo $item['PIC3']; ?>" onclick="setImg(this)">
        <?php } ?>

    </div>

    <div class="main-image">
        <img id="mainImg" src="uploads/<?php echo $item['PIC1']; ?>">
    </div>

</div>

<!-- INFO -->
<div class="info">

    <div class="title"><?php echo $item['TITLE']; ?></div>

    <div class="price">₩ <?php echo number_format($item['PRICE']); ?></div>

    <div class="row"><div class="label">상품번호</div><?php echo $item['ITEMNO']; ?></div>
    <div class="row"><div class="label">카테고리</div><?php echo $item['CATEGORY']; ?></div>
    <div class="row"><div class="label">거래장소</div><?php echo $item['TRADEPLACE']; ?></div>
    <div class="row"><div class="label">판매자</div><?php echo $item['CNO']; ?></div>

    <div class="row">
        <div class="label">설명</div>
        <?php echo nl2br($item['DESCRIPTION']); ?>
    </div>

    <div class="btns">

        <!-- 구매자가 상품을 볼 경우 구매 요청 버튼 표시-->
        <?php if($_SESSION['cno'] != $item['CNO']) { ?>
            <a class="btn buy" href="purchase_request.php?cno=<?=$item['CNO']?>&itemno=<?=$item['ITEMNO']?>">
                구매 요청
            </a>
        <?php } ?>

        <!-- 판매자가 상품을 볼 경우 삭제 버튼 표시-->
        <?php if($_SESSION['cno'] == $item['CNO']) { ?>
            <!-- 상품 삭제 요청-->
            <a class="btn delete"
            href="item_delete.php?itemno=<?=$item['ITEMNO']?>"
            onclick="return confirm('정말 삭제하시겠습니까?');">
            삭제
            </a>
        <?php } ?>

        <a class="btn back" href="main.php">목록으로</a>

    </div>

</div>

</div>

<script>
function setImg(el){
    document.getElementById("mainImg").src = el.src;
}
</script>

</body>
</html>