<?php
include($_SERVER["DOCUMENT_ROOT"]."/class1.php");

if ($_POST['action'] == 'email') {
    echo Email::main();
}