<?php
require_once "includes/model/users.php";

require_once "includes/footer.php";

if (!empty($_POST['login']) && !empty($_POST['password'])) {

    $resultat = getVerification($_POST['login'], $_POST['password']);
    if ($resultat){
        session_start();
        $_SESSION['login'] = $resultat->login;
        $_SESSION['type_id'] = $resultat->type_id;
        $_SESSION['user_id'] = $resultat->user_id;
        header("Location:includes/view/main.php ");

    } else {
        echo "Login ou Password incorrect";
    }
}

echo '<script type="text/javascript" src="/Chalice/includes/assets/js/verifForm.js"></script>
      <link href="/Chalice/includes/assets/css/form.css" rel="stylesheet" media="screen">';

echo'
<form name="index" method="POST"  onsubmit= "return validateForm2(\'index\',\'login\', \'password\'); "  >
    Login : <input type="text" name="login" autocomplete="off" onkeypress="verifierCaracteres(event); return false;">
    Password : <input type="password" name="password" autocomplete="off" >
    <input type="submit" name="submit" value="submit">

</form>';

?>
