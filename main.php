<?php
session_start();

if(!isset($_SESSION['cno'])){
    header("Location: login.php");
    exit();
}

header('Content-Type: text/html; charset=UTF-8');

$conn = oci_connect(
    "d202401721",
    "1111",
    "localhost/XE",
    "AL32UTF8"
);

oci_execute(oci_parse($conn, "ALTER SESSION SET NLS_LANGUAGE='KOREAN'"));

// 검색 조건 입력값 수집
$title = $_GET['title'] ?? "";
$category = $_GET['category'] ?? "";
$seller = $_GET['seller'] ?? "";
$minprice = $_GET['minprice'] ?? "";
$maxprice = $_GET['maxprice'] ?? "";
$sort = $_GET['sort'] ?? "";

$minprice = $_GET['minprice'] ?? "";
$maxprice = $_GET['maxprice'] ?? "";

$op1 = $_GET['op1'] ?? "and";
$op2 = $_GET['op2'] ?? "and";

//상품 검색 SQL생성

$sql = "SELECT * FROM ITEM WHERE 1=1";

$cond1 = "";
$cond2 = "";
$cond3 = "";

//상품명 검색 조건
if($title != ""){
    $cond1 = "TITLE LIKE '%$title%'";
}
//판매자 검색 조건
if($seller != ""){
    $cond2 = "CNO LIKE '%$seller%'";
}

//가격 범위검색 조건
if($minprice != "" && $maxprice != ""){
    $cond3 = "PRICE BETWEEN $minprice AND $maxprice";
}
//논리 연산자 적용 (AND/OR/NOT)
if($cond1 != ""){
    $sql .= " AND ($cond1)";
}

if($cond2 != ""){

    if($op1 == "and"){
        $sql .= " AND ($cond2)";
    }
    else if($op1 == "or"){
        $sql .= " OR ($cond2)";
    }
    else if($op1 == "not"){
        $sql .= " AND NOT ($cond2)";
    }

}

if($cond3 != ""){

    if($op2 == "and"){
        $sql .= " AND ($cond3)";
    }
    else if($op2 == "or"){
        $sql .= " OR ($cond3)";
    }
    else if($op2 == "not"){
        $sql .= " AND NOT ($cond3)";
    }

}
//카테고리 검색
if($category != ""){
    $sql .= " AND CATEGORY='$category'";
}
//정렬 조건 (최신순, 낮은 가격순, 높은 가격 순)
if($sort == "lowprice") $sql .= " ORDER BY PRICE ASC";
else if($sort == "highprice") $sql .= " ORDER BY PRICE DESC";
else $sql .= " ORDER BY ITEMNO DESC";

$stmt = oci_parse($conn, $sql);
oci_execute($stmt);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>중고거래 사이트</title>
<h2>CI/CD TEST SUCCESS</h2>

<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;600;700&display=swap" rel="stylesheet">

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
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:12px 20px;
    position:sticky;
    top:0;
    z-index:1000;
}

.logo{
    font-weight:700;
}

/* NAV IN BLUE BAR */
.nav{
    display:flex;
    gap:10px;
    align-items:center;
}

.nav a{
    color:white;
    text-decoration:none;
    font-size:14px;
    padding:6px 10px;
    border-radius:6px;
}

.nav a:hover{
    background:rgba(255,255,255,0.2);
}

/* USER */
.user{
    position:relative;
    cursor:pointer;

    display:flex;
    align-items:center;
    gap:8px;
}

.user-icon{
    width:34px;
    height:34px;
    border-radius:50%;
    background:white;
    display:flex;
    align-items:center;
    justify-content:center;
    color:#1e88e5;
}

/* DROPDOWN */
.dropdown{
    position:absolute;
    right:0;
    top:45px;
    width:170px;
    background:white;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.15);
    display:none;
}

.dropdown a{
    display:block;
    padding:10px;
    color:#222;
    text-decoration:none;
}

.dropdown a:hover{
    background:#1e88e5;
    color:white;
}

/* LAYOUT */
.layout{
    display:flex;
    gap:15px;
    padding:15px;
}

/* SIDEBAR */
.sidebar{
    width:220px;
    background:white;
    padding:15px;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(0,0,0,0.06);
}

/* CATEGORY TITLE LOOK */
.sidebar a{
    display:block;
    padding:10px 12px;
    margin-bottom:6px;
    text-decoration:none;
    color:#333;
    border-radius:10px;
    font-size:14px;
    transition:0.2s;
}

/* HOVER EFFECT */
.sidebar a:hover{
    background:#1e88e5;
    color:white;
    transform:translateX(4px);
}

/* ACTIVE FEEL (optional) */
.sidebar a:active{
    transform:scale(0.98);
}

/* MAIN */
.main{
    flex:1;
}

/* SEARCH */
.search{
    background:white;
    padding:15px;
    border-radius:12px;
    display:flex;
    gap:10px;
}

/* GRID */
.grid{
    margin-top:15px;
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
}

/* CARD */
.card{
    background:white;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    transition:0.2s;
}

.card:hover{
    transform:translateY(-5px);
}

.img-box{
    position:relative;
    height:220px;
}

.img-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.status{
    margin-top:5px;
    font-size:13px;
    font-weight:bold;
}
.username{
    color:white;
    font-size:14px;
    font-weight:600;
    margin-right:8px;
}

.selling{
    color:#2e7d32;   /* 초록 */
}

.reserved{
    color:#ff9800;   /* 주황 */
}

.sold{
    color:#e53935;   /* 빨강 */
}

/* TEXT */
.info{ padding:12px; }
.title{ font-weight:600; }
.price{ color:#e53935; font-weight:700; }
.date{ font-size:12px; color:#777; }

.card-link{
    text-decoration:none;
    color:inherit;
}
.seller{
    font-size:13px;
    color:#666;
    margin-top:5px;
}
</style>
</head>

<body>

<div class="header">

    <div class="logo">중고거래 사이트</div>

    <div class="nav">
        <a href="main.php">홈</a>
        <a href="item_insert.php">등록</a>
        <a href="chat_list.php">채팅목록</a>
        <a href="purchase_request_list.php">요청목록</a>
        <!--관리자 계정만 통계 메뉴 표시-->
        <?php if($_SESSION['cno'] == 'c0'){ ?>
    <a href="admin_statistics.php" style="
        background:#ff3b30;
        color:white;
        padding:6px 10px;
        border-radius:8px;
        margin-left:8px;
        font-weight:600;
    ">
        🔒 관리자 통계
    </a>
<?php } ?>

    </div>

    <div class="user" onclick="toggle()">
        <span class="username">
            <?php echo $_SESSION['cno']; ?>
        </span>
        <div class="user-icon">👤</div>


        <div class="dropdown" id="dd">
            <a href="item_insert.php">상품 등록</a>
            <a href="logout.php">로그아웃</a>
        </div>
    </div>

</div>

<div class="layout">

<!--카테고리별 상품 조회 메뉴-->
<div class="sidebar">
    <a href="main.php">📋 전체 상품</a>
    <a href="?category=전자기기">전자기기</a>
    <a href="?category=의류">의류</a>
    <a href="?category=도서">도서</a>
    <a href="?category=기타">기타</a>
</div>

<div class="main">

<form class="search" method="GET">

    <input name="title" placeholder="상품명">

    <select name="op1">
        <option value="and">AND</option>
        <option value="or">OR</option>
        <option value="not">NOT</option>
    </select>

    <input name="seller" placeholder="판매자">

    <select name="op2">
        <option value="and">AND</option>
        <option value="or">OR</option>
        <option value="not">NOT</option>
    </select>

    <input name="minprice" placeholder="최소가격">
    <input name="maxprice" placeholder="최대가격">


    <select name="sort">
        <option value="">최신순</option>
        <option value="lowprice">낮은가격</option>
        <option value="highprice">높은가격</option>
    </select>
    
    <button>검색</button>
</form>

<div class="grid">

<?php while($row = oci_fetch_assoc($stmt)) {


?>

<a href="item_view.php?cno=<?php echo $row['CNO']; ?>&itemno=<?php echo $row['ITEMNO']; ?>"
   class="card-link">

<div class="card">

    <div class="img-box">


        <img src="uploads/<?php echo $row['PIC1']; ?>"
             onerror="this.src='https://via.placeholder.com/300';">

    </div>
    
    <div class="info">
        <div class="title"><?php echo $row['TITLE']; ?></div>

        <div class="price">₩ <?php echo number_format($row['PRICE']); ?></div>
    <?php
    $status = trim($row['SELLSTATUS']);

    if($status == '판매 중'){
        $statusClass = 'selling';
    }
    else if($status == '예약 중'){
        $statusClass = 'reserved';
    }
    else{
        $statusClass = 'sold';
    }
    ?>

    <div class="status <?php echo $statusClass; ?>">
        <?php echo $status; ?>
    </div>

        <div class="seller">
        판매자 : <?php echo $row['CNO']; ?>
        </div>
        <div class="date"><?php echo date($row['REGDATETIME']); ?></div>
    </div>

</div>

</a>

<?php } ?>

</div>

</div>
</div>

<script>
function toggle(){
    let d = document.getElementById("dd");
    d.style.display = (d.style.display === "block") ? "none" : "block";
}

document.addEventListener("click", function(e){
    if(!e.target.closest(".user")){
        document.getElementById("dd").style.display = "none";
    }
});
</script>

</body>
</html>