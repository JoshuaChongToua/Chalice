<?php
function getIdImage()
{
    global $pdo;
    $query = "SELECT image_id FROM images ;";
    return $pdo->query($query)->fetch();

}