<?php
require_once "D:\laragon\www\Chalice\includes\common\SPDO.php";
/*function getVerification($login,$password)
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
*/

function getVerification($login, $password)
{
    global $pdo;
    $query = "SELECT * FROM users WHERE login=:login";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':login', $login, PDO::PARAM_STR);
    $prep->execute();
    $result = $prep->fetch(PDO::FETCH_OBJ);

    if ($result && password_verify($password, $result->password)) {
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

function getRole($id){

    global $pdo;
    $query = "SELECT role FROM users_types WHERE type_id =:id;";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':id', $id, PDO::PARAM_INT);
    $prep->execute();
    return $prep->fetchAll();
}

function getTypeId($role)
{
    global $pdo;
    $query = "SELECT * FROM users_types WHERE role =:role;";
    $prep = $pdo->prepare($query);
    $prep->bindValue(':role', $role, PDO::PARAM_STR);
    $prep->execute();
    return $prep->fetchAll();
}