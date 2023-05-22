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


function getAllUsers()
{
    global $pdo;
    $query = 'SELECT * FROM users;';
    return $pdo->query($query)->fetchAll();
}

function getUser($id)
{
    global $pdo;
    $query = 'SELECT * from users where user_id=:id;';
    try {
        $prep = $pdo->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        $result = $prep->fetch();
        return $result;
    }
    catch ( Exception $e ) {
        die("erreur dans la requete ".$e->getMessage());
    }
}