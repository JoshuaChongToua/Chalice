<?php
require_once "../model/images.php";
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
        if (isset($_POST['submit'])) {
            $file = $_FILES['image'];
            $filename = $_POST['imageTitle'];
            $tmpFilePath = $file['tmp_name'];

            // Vérifier si le fichier est une image valide
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

            $destination = '../assets/images/' . $filename . '.' . $fileExtension;
            //echo "<pre>" . print_r($destination, true) . "</pre>";


            // Vérifier si le fichier existe déjà
            if (file_exists($destination)) {
                echo "Le fichier existe déjà.";
            } else {
                // Vérifier si l'extension est autorisée
                if (in_array($fileExtension, $allowedExtensions)) {
                    // Déplacer le fichier téléchargé vers le dossier de destination
                    if (move_uploaded_file($tmpFilePath, $destination)) {
                        // Le fichier a été téléchargé avec succès, vous pouvez effectuer d'autres actions si nécessaire
                        echo "L'image a été téléchargée avec succès.";
                        //recuperer l'id image
                        global $pdo;

                        //$lastInsertedId = $_POST['image_id'];
                        //echo "<pre>" . print_r($lastInsertedId, true) . "</pre>";

                        //$newName =
                        $sql = "INSERT INTO images(name) VALUES (:name);";
                        try {
                            $statement = $pdo->prepare($sql);
                            $statement->bindParam(':name', $filename, PDO::PARAM_STR);
                            $statement->execute();

                            //$destination2 = '../assets/images/' . $lastInsertedId . '.' . $fileExtension;
                            /*if (move_uploaded_file($destination, $destination2)) {
                                echo 'Fichier bien renomee';
                            } else{
                                echo 'echec';
                            }*/


                        } catch (PDOException $e) {
                            echo "Erreur : " . $e->getMessage();
                        }
                    } else {
                        echo "Une erreur s'est produite lors du téléchargement de l'image.";
                    }
                } else {
                    echo "Le format de fichier n'est pas pris en charge. Veuillez sélectionner une image valide.";
                }
            }
        }
    } else if ($action == "update") {
        // si le formulaire a été submit
        if (isset($_POST['imageTitle'])) {
            $title = $_POST['imageTitle'];
            $id = $_POST['image_id'];

            $image = getImageById($id);
            echo "<pre>" . print_r($image, true) . "</pre>";

            if ($image) {
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $filename = $image->name;
                echo "<pre>" . print_r($filename, true) . "</pre>";

                $fileCollection = glob('../assets/images/' . $filename . '.*');
                $tempPath = '../assets/images/' . $filename;
                echo "<pre>" . print_r($tempPath, true) . "</pre>";

                foreach ($fileCollection as $filePath) {
                    echo "<pre>" . print_r($filePath, true) . "</pre>";
                    foreach ($allowedExtensions as $extension) {

                        if ($tempPath . '.' . $extension == $filePath) {
                            $filename = $title . '.' . $extension;
                            echo "<pre>" . print_r($filename, true) . "</pre>";
                        }
                    }
                }
            }


            $sql = "UPDATE images SET name=:title WHERE image_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

        } else {
            $displayForm = true;
            $image = getImageById($id);
        }
    } elseif ($action == "delete" && isset($id)) {
        $image = getImageById($id);
        if ($image) {
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $filename = $image->name;
            $fileCollection = glob('../assets/images/' . $filename . '.*');
            $tempPath = '../assets/images/' . $filename;
            foreach ($fileCollection as $filePath) {
                //echo "<pre>" . print_r($filePath, true) . "</pre>";
                foreach ($allowedExtensions as $extension) {

                    if ($tempPath . '.' . $extension == $filePath) {
                        $filename = $image->name . '.' . $extension;
                        //echo "$filename \n";
                    }
                }
            }

            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $imagePath = '../assets/images/' . $filename;
            //echo "<pre>" . print_r($imagePath, true) . "</pre>";

            if (file_exists($imagePath)) {
                // Supprimer l'image du dossier
                unlink($imagePath);
                // Supprimer l'entrée correspondante dans la base de données
                deleteImageById($id);
                echo "L'image et l'entrée correspondante ont été supprimées avec succès.";
            } else {
                echo "L'image n'a pas été trouvée dans le dossier.";
            }
        } else {
            echo "L'image n'a pas été trouvée dans la base de données.";
        }
    }

}


$imagesCollection = getImages();

$imageDirectory = '../assets/images/';
$images = scandir($imageDirectory);

if ($displayForm) {
    //Formulaire pour upload l'image
    echo '
    <form name="imageForm" action="?action=' . $action . '" method="POST" enctype="multipart/form-data" onsubmit= "return validateForm(\'imageForm\',\'imageTitle\');" onkeypress="verifierCaracteres(event); return false;">
        <input type="text" name="imageTitle" placeholder="imageTitle" autocomplete="off" value="' . ($action == 'update' ? $image->name : '') . '">
        <br>
        <input type="file" name="image" >
        <br>
        <input type="hidden" name="image_id" value="' . ($action == 'update' ? $id : '') . '" >
        <br>
        <input type="submit" name="submit" value="submit">
    </form>
    ';
} else {
    echo '
    <table>
        <tr>
            <th>image_id</th>
            <th>name</th>
            <th>create_date</th>
            <th>edit</th>
            <th>delete</th>
            
        </tr>
    ';

    foreach ($imagesCollection as $imageItem) {
        echo '<tr>';
        echo '<td>' . $imageItem->image_id . '</td>';
        echo '<td>' . $imageItem->name . '</td>';
        echo '<td>' . $imageItem->create_date . '</td>';
        echo '<td><a href="?action=update&image_id=' . $imageItem->image_id . '">edit</a></td>';
        echo '<td><a href="?action=delete&image_id=' . $imageItem->image_id . '">Supprimer</a></td>';


    }


}


if (!$displayForm) {
    foreach ($images as $image) {
        //Pour vérifier que l'image est au bon format
        if (in_array(strtolower(pathinfo($image, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png'])) {
            $imageName = pathinfo($image, PATHINFO_FILENAME);
            echo '<img src="' . $imageDirectory . $image . '" alt="Image" title="' . $imageName . '">' . PHP_EOL;
        }
    }
}


echo '<a href="?action=create">Ajouter</a>';

?>

</body>

