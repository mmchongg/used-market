<?php
session_start();

if(!isset($_SESSION['cno'])){
    header("Location: login.php");
    exit();
}

include("db.php");

//전달받은 채팅방 번호
$roomno = $_GET['roomno'];
//현재 로그인 사용자 번호
$mycno = $_SESSION['cno'];

/* 채팅방 정보 조회*/
$sqlRoom = "
SELECT *
FROM CHATROOM
WHERE ROOMNO = :roomno
";

$stmtRoom = $conn->prepare($sqlRoom);
$stmtRoom->bindParam(':roomno', $roomno);
$stmtRoom->execute();

$chatroom = $stmtRoom->fetch(PDO::FETCH_ASSOC);

//현재 사용자가 판매자인지 확인
$meIsSeller = ($chatroom['CNO'] == $mycno);

/*
S = seller
B = buyer
*/

//상대방이 보낸 메시지를 읽음 처리하기 위해
//상대방의 sender 값 결정
$targetSender = $meIsSeller ? 'B' : 'S';

$sqlRead = "
UPDATE MESSAGE
SET ISREAD = 'Y'
WHERE ROOMNO = :roomno
AND SENDER = :sender
";

$stmtRead = $conn->prepare($sqlRead);
$stmtRead->bindParam(':roomno', $roomno);
$stmtRead->bindParam(':sender', $targetSender);
$stmtRead->execute();

//채팅방의 전체 메시지를 시간순으로 조회
$sql = "
SELECT *
FROM MESSAGE
WHERE ROOMNO = :roomno
ORDER BY SEQNO ASC
";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':roomno', $roomno);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>채팅방</title>

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
    padding:12px;

    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* CHAT BOX */
.chat-box{
    max-width:800px;
    margin:20px auto;
    background:white;
    border-radius:12px;
    padding:15px;
    height:70vh;
    overflow-y:auto;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

/* MESSAGE */
.msg{
    display:flex;
    margin-bottom:12px;
}

.me{
    justify-content:flex-end;
}

.other{
    justify-content:flex-start;
}

/* BUBBLE */
.bubble{
    max-width:60%;
    padding:10px 14px;
    border-radius:18px;
    font-size:14px;
    line-height:1.4;
}

.header-actions{
    display:flex;
    gap:10px;
    align-items:center;
}

.trade-btn,
.home-btn{
    background:#1e88e5;
    color:white;
    text-decoration:none;
    border:none;
    padding:8px 14px;
    border-radius:8px;
    font-size:14px;
    font-weight:600;
    cursor:pointer;
}

.trade-btn:hover,
.home-btn:hover{
    background:#1565c0;
}

/* COLORS */
.me .bubble{
    background:#1e88e5;
    color:white;
}

.other .bubble{
    background:#e9ecef;
    color:#333;
}

/* META */
.meta{
    margin-top:6px;
    display:flex;
    flex-direction:column;
    gap:3px;
    font-size:11px;
}

/* TIME */
.time{
    color:#777;
}

/* STATUS (READ / UNREAD) */
.status{
    font-weight:700;
    font-size:12px;
    padding:2px 6px;
    border-radius:6px;
    width:fit-content;
}

.read{
    background:#d4f8e8;
    color:#1e7e34;
}

.unread{
    background:#ffe5e5;
    color:#c0392b;
}

/* INPUT */
.input-box{
    max-width:800px;
    margin:10px auto;
    display:flex;
    gap:10px;
}

input[type=text]{
    flex:1;
    padding:10px;
    border-radius:10px;
    border:1px solid #ddd;
}

button{
    background:#1e88e5;
    color:white;
    border:none;
    padding:10px 14px;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="header">

    <div>
        채팅방 #<?php echo $roomno; ?>
    </div>

    <div class="header-actions">

<!-- 판매자만 거래 완료 가능-->
<?php if($meIsSeller){ ?>

    <form action="tradecomplete.php"
        method="post"
        style="margin:0;">

        <input type="hidden"
            name="roomno"
            value="<?php echo $roomno; ?>">
        
        <input type="number"
            name="finalprice"
            placeholder="최종 거래금액"
            required>
        <button type="submit" class="trade-btn">
            거래 완료
        </button>

    </form>

    <?php } ?>

        <a href="main.php" class="home-btn">
            메인으로
        </a>

    </div>

</div>

<div class="chat-box">

<?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>

<div class="msg <?php echo ($row['SENDER'] == $targetSender) ? 'other' : 'me'; ?>">

    <div class="bubble">

        <div class="text">
            <!--메시지 내용 출력 -->
            <?php echo htmlspecialchars($row['CONTENT']); ?>
        </div>

        <div class="meta">

            <div class="time">
                <?php echo $row['SENTDATETIME']; ?>
            </div>

            <!--내가 보낸 메시지에 대해서만 읽음 여부 표시-->
            <?php if($row['SENDER'] != $targetSender){ ?>
                <div class="status <?php echo ($row['ISREAD']=='Y') ? 'read' : 'unread'; ?>">
                    <?php echo ($row['ISREAD']=='Y') ? '✔ 읽음' : '⏳ 안읽음'; ?>
                </div>
            <?php } ?>

        </div>

    </div>

</div>

<?php } ?>

</div>

<!-- 메시지 입력 후 chat_send.php로 전송 -->
<form class="input-box" action="chat_send.php" method="post">


<input type="hidden" name="roomno" value="<?php echo $roomno; ?>">

<input type="text" name="content" placeholder="메시지를 입력하세요..." required>

<button type="submit">전송</button>

</form>

</body>
</html>