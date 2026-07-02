<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>로그인</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Segoe UI", Arial, sans-serif;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: linear-gradient(-45deg, #00c6ff, #0072ff, #00f2fe, #005bea);
    background-size: 400% 400%;
    animation: bg 8s ease infinite;
}

@keyframes bg {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.login-box {
    width: 400px;
    padding: 45px;
    background: rgba(255,255,255,0.92);
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
}

.logo {
    text-align: center;
    font-size: 28px;
    font-weight: bold;
    color: #0072ff;
    margin-bottom: 5px;
}

.subtitle {
    text-align: center;
    font-size: 14px;
    color: #666;
    margin-bottom: 25px;
}

.label {
    font-size: 14px;
    margin: 10px 0 6px;
    color: #444;
}

input {
    width: 100%;
    padding: 14px;
    border: 1px solid #ddd;
    border-radius: 10px;
    font-size: 16px;
}

input:focus {
    outline: none;
    border-color: #0072ff;
}

button {
    width: 100%;
    margin-top: 20px;
    padding: 14px;
    background: #0072ff;
    border: none;
    color: white;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
}

button:hover {
    background: #005bd1;
}
</style>

</head>

<body>

<div class="login-box">

    <div class="logo">마켓플레이스</div>

    <form method="post" action="login_ok.php">

        <div class="label">아이디</div>
        <input type="text" name="cno" placeholder="아이디 입력">

        <div class="label">비밀번호</div>
        <input type="password" name="passwd" placeholder="비밀번호 입력">

        <button type="submit">로그인</button>

    </form>

</div>

</body>
</html>