<?php

function getImages()
{
    global $pdo;
    $query = "SELECT * FROM images ;";
    return $pdo->query($query)->fetchAll();

}

function getImageById($id)
{
    global $pdo;
    $query = "SELECT * FROM images WHERE image_id=:id ;";
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

function deleteImageById($id)
{
    $sql = "DELETE FROM images WHERE image_id = :id";
    try {
        global $pdo;
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    } catch (PDOException $e) {
        die("erreur dans la requete " . $e->getMessage());
    }

}

