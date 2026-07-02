<?php
session_start();

if(!isset($_SESSION['cno'])){
    header("Location: login.php");
    exit();
}

include("db.php");

$loginCno = $_SESSION['cno'];

/* CHATROOM LIST */
$sql = "
SELECT *
FROM CHATROOM
WHERE RECEIVECNO = :cno
OR CNO = :cno
ORDER BY ROOMNO DESC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':cno', $loginCno);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>채팅방 목록</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f4f6fb;
}

.header{
    background:#1e88e5;
    color:white;
    padding:15px;
    font-size:18px;
    font-weight:600;
}

/* WRAP */
.container{
    max-width:900px;
    margin:30px auto;
}

/* CARD */
.chat-card{
    background:white;
    padding:15px;
    margin-bottom:12px;
    border-radius:14px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.06);
    transition:0.2s;
}

.chat-card:hover{
    transform:translateY(-3px);
}

/* INFO */
.info{
    display:flex;
    flex-direction:column;
    gap:6px;
}

.room{
    font-size:12px;
    color:#888;
}

.item{
    font-weight:600;
}

.badge{
    display:inline-block;
    background:red;
    color:white;
    font-size:11px;
    padding:3px 8px;
    border-radius:20px;
}

/* BUTTON */
.enter{
    background:#1e88e5;
    color:white;
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
    font-size:13px;
}
.enter:hover{
    background:#1565c0;
}
</style>
</head>

<body>

<div class="header">
채팅방 목록
</div>

<div class="container">

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>

<?php
/* COUNT UNREAD MESSAGES PER ROOM */
$sqlCount = "
SELECT COUNT(*) AS CNT
FROM MESSAGE
WHERE ROOMNO = :roomno
AND ISREAD = 'N'
AND SENDER != :me
";

$stmtCount = $conn->prepare($sqlCount);
$stmtCount->bindParam(':roomno', $row['ROOMNO']);
$stmtCount->bindParam(':me', $loginCno);
$stmtCount->execute();

$countRow = $stmtCount->fetch(PDO::FETCH_ASSOC);
$cnt = $countRow['CNT'] ?? 0;
?>

<div class="chat-card">

    <div class="info">

        <div class="room">
            ROOM #<?php echo $row['ROOMNO']; ?>
        </div>

        <div class="item">
            ITEM #<?php echo $row['ITEMNO']; ?>
        </div>
        <div>
            판매자 : <?php echo $row['CNO']; ?>
        </div>

        <div>
            구매자 : <?php echo $row['RECEIVECNO']; ?>
        </div>

        <?php if($cnt > 0){ ?>
            <div class="badge">
                🔴 새 메시지 (<?php echo $cnt; ?>)
            </div>
        <?php } ?>

    </div>

    <a class="enter"
       href="chat.php?roomno=<?php echo $row['ROOMNO']; ?>">
        채팅입장
    </a>

</div>

<?php } ?>

</div>

</body>
</html>