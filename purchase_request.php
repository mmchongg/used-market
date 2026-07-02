<?php
session_start();

$cno = $_GET['cno'];
$itemno = $_GET['itemno'];
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>구매 요청</title>

<style>
body{
    font-family:Arial;
    background:#f4f6fb;
    margin:0;
}

.box{
    max-width:500px;
    margin:60px auto;
    background:white;
    padding:20px;
    border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

h2{
    margin-bottom:20px;
}

input, textarea{
    width:100%;
    padding:10px;
    margin-top:8px;
    margin-bottom:15px;
    border:1px solid #ddd;
    border-radius:8px;
}

button{
    background:#1e88e5;
    color:white;
    border:none;
    padding:10px 14px;
    border-radius:8px;
    cursor:pointer;
    width:100%;
}
</style>
</head>

<body>

<div class="box">

<h2>구매 요청</h2>

<form action="purchase_request_ok.php" method="post">

<input type="hidden" name="cno" value="<?php echo htmlspecialchars($cno); ?>">
<input type="hidden" name="itemno" value="<?php echo htmlspecialchars($itemno); ?>">

<label>희망 가격</label>
<input type="number" name="reqprice" required>

<label>메시지</label>
<textarea name="reqmessage" rows="5"></textarea>

<button type="submit">요청 보내기</button>

</form>

</div>

</body>
</html>