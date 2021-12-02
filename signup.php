<?php
include($_SERVER["DOCUMENT_ROOT"]."/class1.php");
user::func_check_profile();

include ($_SERVER["DOCUMENT_ROOT"].'/header.php');
?>

<div class="cont-form">
    <form action="/answer.php" method="post">
        <div class="title">Регистрация</div>
        <input type="hidden" name="type_m" value="signup">
        <div class="cont-label">
            <label for="">Логин</label>
            <input class="input" name="login" placeholder="nikita222" type="text" value="<?= ($_SESSION['reg']['login'] ? $_SESSION['reg']['login'] : '') ?>">
        </div>
        <div class="cont-label">
            <label for="">E-mail</label>
            <input class="input" name="email" placeholder="qwerty@mail.ru" type="text" value="<?= ($_SESSION['reg']['email'] ? $_SESSION['reg']['email'] : '') ?>">
        </div>
        <div class="cont-label">
            <label for="">Телефон</label>
            <input class="input" name="phone" placeholder="89000000000" type="text" value="<?= ($_SESSION['reg']['phone'] ? $_SESSION['reg']['phone'] : '') ?>">
        </div>
        <div class="cont-label">
            <label for="">Пароль:</label>
            <input class="input" name="password" type="password">
        </div>
        <div class="cont-label">
            <label for="">Повторите пароль:</label>
            <input class="input" name="password1" type="password">
        </div>

        <?php if ($_SESSION['message']): ?>
            <div class="message">
                <span><?= $_SESSION['message'] ?></span>
            </div>
            <?php unset($_SESSION['message']); endif; ?>
        <div class="sign">
            <button type="submit" class="a1" id="registraciya">Зарегестрироваться</button>
            <a class="a2" href="signin.php">Войти</a>
        </div>

    </form>
</div>

<?php
include ($_SERVER["DOCUMENT_ROOT"].'/footer.php');
?>
