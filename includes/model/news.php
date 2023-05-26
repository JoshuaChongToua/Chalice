<?php

function getAllNews()
{
    global $pdo;
    $query = 'SELECT * FROM news;';
    return $pdo->query($query)->fetchAll();
}

function getNews($id)
{
    global $pdo;
    $query = 'SELECT * from news where news_id=:id;';
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
