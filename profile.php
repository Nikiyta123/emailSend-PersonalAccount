<?php

include ($_SERVER["DOCUMENT_ROOT"].'/class1.php');
user::func_check_auth();

include ($_SERVER["DOCUMENT_ROOT"].'/header.php');
?>

<h3>Ваш логин - <?= $_SESSION['user']['login'] ?></h3>
<h3>Ваш e-mail - <?= $_SESSION['user']['email'] ?></h3>
<h3>Ваш телефон - <?= $_SESSION['user']['phone'] ?></h3>
<a href="/logout.php" class="logout">Выход</a>

<?php
include ($_SERVER["DOCUMENT_ROOT"].'/footer.php');
?>
