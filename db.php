<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'authot_user';

$workdb = 'user';

$db = new mysqli($host, $username, $password,$dbname);
if (!$db){
    die('Нету');
}
