<?php
session_start();

if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}

include("db.php");

$roomno = $_POST['roomno'];
$content = $_POST['content'];

$loginCno = $_SESSION['cno'];


/*
채팅방 정보 조회
*/

$sql = "
SELECT *
FROM CHATROOM
WHERE ROOMNO = :roomno
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':roomno', $roomno);

$stmt->execute();

$chatroom = $stmt->fetch(PDO::FETCH_ASSOC);


/*
판매자면 S
구매자면 B
*/

if($loginCno == $chatroom['CNO'])
{
    $sender = 'S';
}
else
{
    $sender = 'B';
}


/*
메시지 저장
*/

$sql = "
INSERT INTO MESSAGE
(
    ROOMNO,
    SENDER,
    SENTDATETIME,
    CONTENT,
    ISREAD
)
VALUES
(
    :roomno,
    :sender,
    SYSTIMESTAMP,
    :content,
    'N'
)
";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':roomno', $roomno);
$stmt->bindParam(':sender', $sender);
$stmt->bindParam(':content', $content);

$stmt->execute();


header("Location: chat.php?roomno=".$roomno);
exit();
?>