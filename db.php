<?php

$host = "localhost";
$port = "1521";
$service = "XE";

$username = "d202401721";
$password = "1111";

try {

    $dsn = "oci:dbname=//$host:$port/$service;charset=UTF8";

    $conn = new PDO(
        $dsn,
        $username,
        $password,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );

}
catch(PDOException $e) {

    die("DB 연결 실패 : " . $e->getMessage());

}

?>