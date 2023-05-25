<?php
require_once "../model/Images.php";
require_once '../header.php';
require_once '../footer.php';



$displayForm = false;

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['image_id'])) {
    $id = $_GET['image_id'];
}

if (isset($action)) {
    if ($action == "create") {
        $displayForm = true;
        // si le formulaire a été submit
        if (isset($_POST['role'])) {
            $role = $_POST['role'];

            $sql = "INSERT INTO users_types(role) VALUES (:role);";
            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':role', $role, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            // on retire le formulaire
            $displayForm = false;

        }
    } else if ($action == "delete" && !empty($id)) {

        $sql = "DELETE FROM users_types WHERE type_id = :id";

        try {
            global $pdo;
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            die("erreur dans la requete " . $e->getMessage());
        }

    }
}
?>


<?php

if ($displayForm) {
    //Formulaire pour upload l'image
    echo '
    <form action="?action=' . $action . '" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" required>
        <br>
        <input type="submit" name="submit" value="submit">
    </form>
    ';
} else {
    echo '
    <table>
        <tr>
            <th>type_id</th>
            <th>create_date</th>
        </tr>
    ';
}

$imageDirectory = '../assets/images/';
$images = scandir($imageDirectory);
if(!$displayForm) {
    foreach ($images as $image) {
        //Pour vérifier que l'image est au bon format
        if (in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])) {

            echo '<img src="' . $imageDirectory . $image . '" alt="Image">' . PHP_EOL;
        }


    }
}


echo '<a href="?action=create">Ajouter</a>';

?>

</body>

