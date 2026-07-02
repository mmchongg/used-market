<?php
session_start();

if(!isset($_SESSION['cno']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>상품 등록</title>

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

/* MAIN BOX */
.container{
    max-width:650px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

/* TITLE */
h2{
    margin-bottom:20px;
}

/* LABEL STYLE (keep simple, no changes to logic) */
label{
    display:block;
    margin-top:12px;
    font-size:14px;
    color:#333;
}

/* INPUT STYLE */
input[type=text],
input[type=number],
select,
textarea{
    width:100%;
    padding:10px;
    margin-top:6px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:14px;
    box-sizing:border-box;
}

textarea{
    resize:none;
}

/* FILE INPUT */
input[type=file]{
    margin-top:6px;
}

/* BUTTON */
input[type=submit]{
    margin-top:18px;
    width:100%;
    padding:12px;
    background:#1e88e5;
    color:white;
    border:none;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
}

input[type=submit]:hover{
    background:#1565c0;
}

/* BACK LINK */
a{
    display:block;
    margin-top:15px;
    text-align:center;
    color:#666;
    text-decoration:none;
}

a:hover{
    color:#000;
}
</style>

</head>

<body>

<div class="header">
상품 등록
</div>

<div class="container">

<h2>상품 등록</h2>

<!--상품 등록 폼-->
<form action="item_insert_ok.php"
method="post"
enctype="multipart/form-data">

제목 :
<input type="text" name="title" required>

<br><br>

가격 :
<input type="number" name="price" required>

<br><br>

카테고리 :
<select name="category">
    <option value="전자기기">전자기기</option>
    <option value="의류">의류</option>
    <option value="도서">도서</option>
    <option value="기타">기타</option>
</select>

<br><br>

거래장소 :
<input type="text" name="tradeplace">

<br><br>

설명 :
<br>
<textarea name="description" rows="5" cols="50"></textarea>

<br><br>

사진1 :
<input type="file" name="pic1">

<br><br>

사진2 :
<input type="file" name="pic2">

<br><br>

사진3 :
<input type="file" name="pic3">

<br><br>

<input type="submit" value="상품 등록">

</form>

<br>

<a href="main.php">메인으로</a>

</div>

</body>
</html>