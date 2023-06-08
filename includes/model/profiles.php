<?php

function getProfileById($id)
{
    global $pdo;
    $query = 'SELECT * from profile where user_id=:id;';
    try {
        $prep = $pdo->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return  $prep->fetch();
    }
    catch ( Exception $e ) {
        die("erreur dans la requete ".$e->getMessage());
    }
}

function getImagesProfileById($id)
{
    global $pdo;
    $query = "SELECT * FROM images_profile  WHERE user_id=:id;";
    try {
        $prep = $pdo->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetchAll();

    }
    catch ( Exception $e ) {
        die("erreur dans la requete ".$e->getMessage());
    }
}