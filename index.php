<?php
require_once "includes/model/users.php";

require_once "includes/footer.php";
echo '<script type="text/javascript" src="/Chalice/includes/assets/js/verifForm.js"></script>
      <link href="/Chalice/includes/assets/css/form.css" rel="stylesheet" media="screen">';


echo'
<form name="index" method="POST"  onsubmit= "return validateForm2(\'index\',\'login\', \'password\'); "  >
    Login : <input type="text" name="login" autocomplete="off" onkeypress="verifierCaracteres(event); return false;">
    Password : <input type="password" name="password" autocomplete="off" >
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
            header("Location:includes/view/main.php ");
        }


    } else {
        echo "Login ou Password incorrect";
    }
}


?>
