<?php
require_once "inc/Users.php";
?>

<form method="POST">
    Login : <input type="text" name="login" >
    Password : <input type="password" name="password" >
    <input type="submit" name="submit" value="submit">

</form>

<?php
if (!empty($_POST['login'])){
    $login=$_POST['login'];
}
else
    $login='';

if (!empty($_POST['password'])){
    $password=$_POST['password'];
}
else
    $password='';

echo"$login $password";
?>
<br>
<?php

if (getVerification($login,$password)){
    echo"ok";
}

else
    echo"ko";
