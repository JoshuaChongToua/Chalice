<?php
require_once "includes/model/Users.php";

require_once "includes/footer.php";

echo'
<form name="index" method="POST"  onsubmit= "return validateForm2(\'index\',\'login\', \'password\'); " required>
    Login : <input type="text" name="login" autocomplete="off">
    Password : <input type="password" name="password" autocomplete="off">
    <input type="submit" name="submit" value="submit">

</form>';


if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (getVerification($login, $password)) {
        session_start();
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        if (isset($_SESSION['login']) && isset($_SESSION['password'])) {
            header("Location:includes/assets/view/main.php ");
        }


    } else {
        echo "Login ou Password incorrect";
    }
}


?>
