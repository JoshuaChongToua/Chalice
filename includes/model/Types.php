<?php

function getAllTypes()
{
    global $pdo;
    $query = 'SELECT * FROM users_types;';
    return $pdo->query($query)->fetchAll();
}

function get_Type($id)
{
    global $pdo;
    $query = 'SELECT * from users_types where type_id=:id;';
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