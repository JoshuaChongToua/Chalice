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

function getImagesProfile()
{
    global $pdo;
    $query = "SELECT * FROM images_profile ;";
    return $pdo->query($query)->fetchAll();

}

function getImageProfileById($id)
{
    global $pdo;
    $query = "SELECT * FROM images_profile WHERE image_id=:id ;";
    try {
        $prep = $pdo->prepare($query);
        $prep->bindParam(':id', $id, PDO::PARAM_INT);
        $prep->execute();
        return $prep->fetch();

    }
    catch ( Exception $e ) {
        die("erreur dans la requete ".$e->getMessage());
    }
}
function deleteImageProfileById($id)
{
    $sql = "DELETE FROM images_profile WHERE image_id = :id";
    try {
        global $pdo;
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    } catch (PDOException $e) {
        die("erreur dans la requete " . $e->getMessage());
    }

}