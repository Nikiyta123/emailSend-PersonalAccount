<?php
session_start();

include ($_SERVER["DOCUMENT_ROOT"].'/db.php');
include($_SERVER["DOCUMENT_ROOT"].'/phpmailer/PHPMailerAutoload.php');


class User
{

    public static function debug($v)
    {
        echo "<pre>";
        print_r($v);
    }

    private static $type;

    private static $login;

    private static $email;

    private static $phone;

    private static $password;

    private static $password1;

    private static $remember;

    private static $errors = [

        0 => 'Логин должен содержать от 5 до 20 символов',
        1 => 'Логин содержит запрещенные символы',
        2 => 'Такой Логин уже существует',

        3 => 'E-mail указан не верно',
        4 => 'Такой E-mail уже существует',

        5 => 'Телефон должен состоять из 11 чисел',
        6 => 'Телефон содержит запрещенные символы',
        7 => 'Такой Телефон уже существует',

        8 => 'Вы не ввели Пароль',
        9 => 'Пароль должен состоять из латинских букв или цифр',
        10 => 'Подтвердите пароль',
        11 => 'Пароли не совпадают',

        12 => 'Укажите ваше имя',
        13 => 'Имя содержит запрещенные символы',
        14 => 'Пароли не совпадают',

    ];

    protected static function func_error($data, $p = '')
    {

        if ($p){
            return self::$errors[$data];
        }

        $_SESSION['message'] = self::$errors[$data];
        header('Location: '.self::$type.'.php');
        die();
    }

    private static function func_get_sql($field, $data, $number)
    {
        global $db, $workdb;

        $sql = "SELECT * FROM `$workdb` WHERE $field = '$data'";
        $res = $db->query($sql);

        if ($res->num_rows == 0) {
            return true;
        }

        self::func_error($number);

    }

    private static function func_login()
    {
        if (strlen(self::$login) >= 5 && strlen(self::$login) <= 20) {
            if (preg_match("/^[A-Za-z0-9]+$/", self::$login)) {
                return true;
            } else {
                self::func_error(1);
            }
        } else {
            self::func_error(0);
        }
    }

    private static function func_email()
    {
        if (filter_var(self::$email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            self::func_error(3);
        }
    }

    private static function func_phone()
    {
        if (strlen(self::$phone) == 11) {
            if (preg_match("/^\d+$/", self::$phone)) {
                return true;
            } else {
                self::func_error(6);
            }
        } else {
            self::func_error(5);
        }
    }

    private static function func_confirm_password($type)
    {
        if (self::$password) {
            if (preg_match("/^[A-Za-z0-9]+$/", self::$password)) {
                if ($type == 'reg') {
                    if (self::$password1) {
                        if (self::$password === self::$password1) {
                            return true;
                        } else {
                            self::func_error(11);
                        }
                    } else {
                        self::func_error(10);
                    }
                } else {
                    return true;
                }
            } else {
                self::func_error(9);
            }
        } else {
            self::func_error(8);
        }
    }

    private static function func_register()
    {
        global $db, $workdb;

        $password = md5(self::$password);

        $sql = "INSERT INTO `$workdb` (`login`,`email`,`phone`,`password`) VALUES ('".self::$login."','".self::$email."','".self::$phone."','".$password."')";
        $db->query($sql);

    }

    private static function func_auth()
    {
        global $db, $workdb;

        $password = md5(self::$password);

        $sql = "SELECT * FROM `$workdb` WHERE `email` = '".self::$email."' AND `password` = '$password'";
        $res = $db->query($sql);

        if ($res->num_rows != 0) {
            $data = $res->fetch_assoc();
            return $data['id'];
        } else {
            $_SESSION['message'] = 'Логин или пароль введены не верно';
            header('Location: ' . self::$type . '.php');
            die();
        }

    }

    private static function func_remember($id)
    {
        global $db, $workdb;

        if (self::$remember) {

            $password_cookie_token = md5($id . self::$password . time());

            $db->query("UPDATE `$workdb` SET `key_value`='$password_cookie_token' WHERE `id` = $id");

            setcookie("password_cookie_token", $password_cookie_token, time() + 60 * 60 * 24 * 30);

        } else {

            $sql = "SELECT * FROM `$workdb` WHERE `id` = $id";
            $res = $db->query($sql);
            $data = $res->fetch_assoc();

            setcookie("id", $data['id'], -1, '/');
            setcookie("login", $data['login'], -1, '/');
            setcookie("email", $data['email'], -1, '/');
            setcookie("phone", $data['phone'], -1, '/');

            $db->query("UPDATE `$workdb` SET `key_value`='' WHERE `id` = $id");

            setcookie("password_cookie_token", "", time() - 3600);

        }

    }

    public static function func_check_auth()
    {
        if (!isset($_COOKIE["password_cookie_token"]) && !$_COOKIE['login']) {
            header('Location: signin.php');
            die();
        }
    }

    public static function func_check_profile()
    {
        if ((isset($_COOKIE["password_cookie_token"]) && !empty($_COOKIE["password_cookie_token"])) || $_COOKIE['login']) {
            header('Location: profile.php');
            die();
        }
    }

    public static function func_logout()
    {
        setcookie("password_cookie_token", "", -1);
        setcookie("login", "", -1);
        session_destroy();
        header('Location: signin.php');
        die();
    }

    public static function main(){

        self::$type = $_POST['type_m'];

        if (self::$type == 'signup')
        {

            self::$login = htmlspecialchars(trim($_POST['login']));
            self::$email = htmlspecialchars(trim($_POST['email']));
            self::$phone = htmlspecialchars(trim($_POST['phone']));
            self::$password = htmlspecialchars(trim($_POST['password']));
            self::$password1 = htmlspecialchars(trim($_POST['password1']));

            $_SESSION['reg']['login'] = self::$login;
            $_SESSION['reg']['email'] = self::$email;
            $_SESSION['reg']['phone'] = self::$phone;

            self::func_login();
            self::func_get_sql('login', self::$login, 2);

            self::func_email();
            self::func_get_sql('email', self::$email, 4);

            self::func_phone();
            self::func_get_sql('phone', self::$phone, 7);

            self::func_confirm_password('reg');

            self::func_register();

            $_SESSION['message'] = 'Регистрация прошла успешно';
            header('Location: signin.php');
            die();

        }
        if ( self::$type == 'signin') {

            self::$email = htmlspecialchars(trim($_POST['email']));
            self::$password = htmlspecialchars(trim($_POST['password']));
            self::$remember = $_POST['remember'];;

            $_SESSION['reg']['email'] = self::$email;

            self::func_email();

            self::func_confirm_password('auth');

            $id = self::func_auth();

            self::func_remember($id);

            header('Location: profile.php');
            die();
        }
    }

}

class Email extends User
{
    private static $type;

    private static $login;

    private static $email;

    private static $phone;

    private static function func_login_email()
    {
        if (!empty(self::$login)) {
            if (preg_match( '/^([а-яА-ЯЁёa-zA-Z_]+)$/u', self::$login)) {
                return false;
            } else {
                return self::func_error(13,self::$type);
            }
        } else {
            return self::func_error(12,self::$type);
        }
    }

    private static function func_phone()
    {
        if (strlen(self::$phone) == 11) {
            if (preg_match("/^\d+$/", self::$phone)) {
                return false;
            } else {
                return self::func_error(6,self::$type);
            }
        } else {
            return self::func_error(5,self::$type);
        }
    }

    private static function func_email($data)
    {
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return self::func_error(3,self::$type);
        }
    }

    private static function content_email($mail){
        $mail->Subject = 'Тестовое задание Автор';
        $mail->Body    = 'Имя - ' .self::$login . ' Телефон - ' .self::$phone;
        $mail->AltBody = '';
    }

    private static function final_email($mail){
        $mail->CharSet = 'utf-8';

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'forauthortestnikita@gmail.com';
        $mail->Password = 'SDFGh54KS';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('forauthortestnikita@gmail.com');

        foreach (self::$email as $item){
            $mail->AddCC($item);
        }

        $mail->isHTML(true);

        self::content_email($mail);

        if(!$mail->send()) {
            var_dump($mail->ErrorInfo);
            return 'Ошибка при отправке';
        }

        return 'Отправлено';
    }

    public static function main(){

        self::$type = $_POST['type_m'];
        self::$login = htmlspecialchars(trim($_POST['login']));
        self::$phone = htmlspecialchars(trim($_POST['phone']));
        self::$email = htmlspecialchars(trim($_POST['email']));

        self::$email = explode(',',self::$email);

        if ($result = self::func_login_email()) {return $result;}

        if ($result = self::func_phone()) {return $result;}

        foreach (self::$email as $item){
            if ($result = self::func_email($item)) {return $result;}
        }

        return self::final_email($mail = new PHPMailer);
    }

}


?>