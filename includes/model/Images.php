<?php

function getImages()
{
    global $pdo;
    $query = "SELECT * FROM images ;";
    return $pdo->query($query)->fetchAll();

}