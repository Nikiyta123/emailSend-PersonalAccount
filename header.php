<?php

session_start();

include ($_SERVER["DOCUMENT_ROOT"].'/db.php');

global $db,$workdb;

if(isset($_COOKIE["password_cookie_token"]) && !empty($_COOKIE["password_cookie_token"])){
    $c = $_COOKIE['password_cookie_token'];
    $sql = "SELECT * FROM `$workdb` WHERE key_value ='$c'";
    $select_user_data = $db->query($sql);

    $data = $select_user_data->fetch_assoc();

    if($data){

        $_SESSION['user']['login'] = $data['login'];
        $_SESSION['user']['email'] = $data['email'];
        $_SESSION['user']['phone'] = $data['phone'];

    }

}elseif ($_COOKIE["login"]){

    $_SESSION['user']['login'] = $_COOKIE["login"];
    $_SESSION['user']['email'] = $_COOKIE["email"];
    $_SESSION['user']['phone'] = $_COOKIE["phone"];
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>