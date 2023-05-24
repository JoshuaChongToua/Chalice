<html>
<?php
$isLaragon = "";
if ($_SERVER['HTTP_HOST'] == "localhost") {
    $isLaragon = "/Chalice";
}


echo '
<head>
    <title>Chalice</title>
    <link href="' . $isLaragon . '/includes/assets/css/userCrud.css" rel="stylesheet" media="screen">
    <link href="' . $isLaragon . '/includes/assets/css/header.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="' . $isLaragon . '/includes/assets/js/verifForm.js"></script>
</head>
<body>

<div class="page">
    <a href="' . $isLaragon . '/includes/assets/view/Users.php">User</a>
    <a href="' . $isLaragon . '/includes/assets/view/News.php">News</a>
    <a href="' . $isLaragon . '/includes/assets/view/Types.php">Type</a>
</div>
';

?>
