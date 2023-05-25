<?php
session_start();

require_once 'common/SPDO.php';

$isLaragon = "";
if ($_SERVER['HTTP_HOST'] == "localhost") {
    $isLaragon = "/Chalice";
}
//echo "<pre>" . print_r($_SESSION['login'], true) . "</pre>";


//echo $_SERVER['PHP_SELF'];
if ($_SERVER['PHP_SELF'] != 'index.php') {
    if (!isset($_SESSION['login'])) {
        header("Location:../../../index.php ");
    }
}




echo '
<html>
<head>
    <title>Chalice</title>
    <link href="' . $isLaragon . '/includes/assets/css/userCrud.css" rel="stylesheet" media="screen">
    <link href="' . $isLaragon . '/includes/assets/css/header.css" rel="stylesheet" media="screen">
    <link href="' . $isLaragon . '/includes/assets/css/images.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="' . $isLaragon . '/includes/assets/js/verifForm.js"></script>
</head>
<body>

<div class="page">
    <a href="' . $isLaragon . '/includes/view/Users.php">User</a>
    <a href="' . $isLaragon . '/includes/view/News.php">News</a>
    <a href="' . $isLaragon . '/includes/view/Types.php">Type</a>
    <a href="' . $isLaragon . '/includes/view/Images.php">Images</a>';
    if (isset($_SESSION['login'])){
        echo "<p> Bienvenue : " .  $_SESSION['login'] . "";
        echo "<a href='/Chalice/includes/logout.php' >Se deconecter</a> ";
    }
    echo'
</div>
';

?>
