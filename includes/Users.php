<?php
require_once 'SPDO.php';
function getVerification($login,$password)
{
    global $pdo;
    $query = "SELECT * FROM users WHERE login=:login and password=:password";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':login', $login, PDO::PARAM_STR);
    $prep->bindValue(':password', $password, PDO::PARAM_STR);
    $prep->execute();
    // on vérifie que la requête ne retourne qu'une seule ligne
    if ($prep->rowCount() == 1) {
        $result = $prep->fetch();
        return $result;
    } else {
        return false;
    }
}