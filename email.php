<?php
session_start();
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

<div class="cont-form">
    <form action="" method="post">
        <div class="title">Почта</div>
        <input type="hidden" id="type_m" name="type_m" value="email">

        <div class="cont-label">
            <label for="">Имя</label>
            <input class="input" id="login" name="login" placeholder="nikita222" type="text" value="<?= ($_SESSION['reg']['login'] ? $_SESSION['reg']['login'] : '') ?>">
        </div>

        <div class="cont-label">
            <label for="">Телефон</label>
            <input class="input" id="phone" name="phone" placeholder="89000000000" type="text" value="<?= ($_SESSION['reg']['phone'] ? $_SESSION['reg']['phone'] : '') ?>">
        </div>

        <div class="cont-label">
            <label for="">E-mail</label>
            <textarea class="input" id="email" name="email" cols="30" rows="1"></textarea>
        </div>
        <span>*Для отправки письма на нескольким адресатам - указывайте почту через запятую (123@mail.ru,23432@mail.ru)</span>

        <div style="width: 100%;text-align: center">
            <span style="color: red;" id="span-message"></span>
        </div>

        <div class="sign">
            <a class="a1" id="registraciya">Отправить</a>
        </div>

    </form>
</div>

<script src="js/email.js"></script>
</body>
</html>


