<?php
require_once 'D:\laragon\www\Chalice\includes\common\SPDO.php';

function getIdImage()
{
    global $pdo;
    $query = "SELECT * FROM images ;";
    return $pdo->query($query)->fetchAll();

}