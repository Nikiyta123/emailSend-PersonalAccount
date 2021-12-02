<?php
include ($_SERVER["DOCUMENT_ROOT"].'/class1.php');
user::func_check_profile();

include ($_SERVER["DOCUMENT_ROOT"].'/header.php');
?>

<div class="cont-form">
    <form action="/answer.php" method="post">
        <div class="title">Вход</div>
        <input type="hidden" name="type_m" value="signin">

        <div class="cont-label">
            <label for="">E-mail</label>
            <input class="input" name="email" placeholder="qwerty@mail.ru" type="text" value="<?= ($_SESSION['reg']['email'] ? $_SESSION['reg']['email'] : '') ?>">
        </div>

        <div class="cont-label">
            <label for="">Пароль:</label>
            <input class="input" name="password" type="text">
        </div>

        <div class="cont-label">
            <label for="remember">
                <input name="remember" type="checkbox" checked="checked" id="remember">
                Запомнить меня
            </label>
        </div>

        <?php if ($_SESSION['message']): ?>
            <div class="message">
                <span><?= $_SESSION['message'] ?></span>
            </div>
            <?php unset($_SESSION['message']); endif; ?>
        <div class="sign">
            <button type="submit" class="a1" id="registraciya">Войти</button>
            <a class="a2" href="/signup.php">Зарегестрироваться</a>
        </div>

    </form>
</div>

<?php
include ($_SERVER["DOCUMENT_ROOT"].'/footer.php');
?>
