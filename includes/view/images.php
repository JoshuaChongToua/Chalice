<?php
require_once '../header.php';
require_once "../model/images.php";


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

            $destination = '../assets/images/upload/' . kodex_random_string($length=10) . '.' . $fileExtension;
            //echo "<pre>" . print_r($destination, true) . "</pre>";


            // Vérifier si l'extension est autorisée
            if (in_array($fileExtension, $allowedExtensions)) {
                // Déplacer le fichier téléchargé vers le dossier de destination
                if (move_uploaded_file($tmpFilePath, $destination)) {
                    // Le fichier a été téléchargé avec succès, vous pouvez effectuer d'autres actions si nécessaire
                    echo "L'image a été téléchargée avec succès.";
                    //recuperer l'id image
                    global $pdo;


                    //$newName =
                    $sql = "INSERT INTO images(name) VALUES (:name);";
                    try {
                        $statement = $pdo->prepare($sql);
                        $statement->bindParam(':name', $filename, PDO::PARAM_STR);
                        $statement->execute();

                        $destination2 = '../assets/images/upload/' . $pdo->lastInsertId() . '.' . $fileExtension;
                        echo $destination;
                        echo '<br>';
                        echo $destination2;
                        if (rename($destination, $destination2)) {
                            echo 'Fichier bien renomee';
                        } else {
                            echo 'echec';
                        }
                        $displayForm = false;

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

    } else if ($action == "update") {
        // si le formulaire a été submit
        if (isset($_POST['imageTitle'])) {
            $title = $_POST['imageTitle'];
            $id = $_POST['image_id'];

            $image = getImageById($id);
            //echo "<pre>" . print_r($image, true) . "</pre>";

            if ($image) {
                $allowedExtensions = ['jpg', 'jpeg', 'png'];
                $filename = $image->name;
                //echo "<pre>" . print_r($filename, true) . "</pre>";

                $fileCollection = glob('../assets/images/upload/' . $filename . '.*');
                $tempPath = '../assets/images/upload/' . $filename;
                //echo "<pre>" . print_r($tempPath, true) . "</pre>";

                foreach ($fileCollection as $filePath) {
                    echo "<pre>" . print_r($filePath, true) . "</pre>";
                    foreach ($allowedExtensions as $extension) {

                        if ($tempPath . '.' . $extension == $filePath) {
                            $filename = $title . '.' . $extension;
                            //echo "<pre>" . print_r($filename, true) . "</pre>";
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
            $filename = $image->image_id;
            $fileCollection = glob('../assets/images/upload/' . $filename . '.*');
            $tempPath = '../assets/images/upload/' . $filename;
            foreach ($fileCollection as $filePath) {
                //echo "<pre>" . print_r($filePath, true) . "</pre>";
                foreach ($allowedExtensions as $extension) {

                    if ($tempPath . '.' . $extension == $filePath) {
                        $filename = $image->image_id . '.' . $extension;
                        echo "<pre>" . print_r($filename, true) . "</pre>";
                    }

                }
            }

            $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            $imagePath = '../assets/images/upload/' . $filename;
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

$imageDirectory = '../assets/images/upload';
$images = scandir($imageDirectory);

echo '<div class="container">';

if ($displayForm) {
    //Formulaire pour upload l'image
    echo '
    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Formulaire</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
                                        
    <form class="form-valide" name="imageForm" action="?action=' . $action . '" method="POST" enctype="multipart/form-data" onsubmit= "return validateForm(\'imageForm\',\'imageTitle\');" onkeypress="verifierCaracteres(event); return false;">
            <div class="form-group row ">                             
        <label class="col-lg-3 col-form-label" for="title">Title :<span class="text-danger">*</span></label>
        <div class="col-lg-9">
        <input class="form-control" type="text" name="imageTitle" placeholder="imageTitle" autocomplete="off" value="' . ($action == 'update' ? $image->name : '') . '">
        </div>
    </div>
        
        <br>';
    if ($action != 'update') {
        echo '
        <input type="file" name="image" >';
    }
    echo '<br>
        <input type="hidden" name="image_id" value="' . ($action == 'update' ? $id : '') . '" >
        <br>
        
        <a class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" href="images.php"><i class="ti-back-left"></i></span>Retour</a>
        <button type="submit" name="submit"  class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>
    </form>
    ';
} else {
    echo '
<div id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="jsgrid-table-panel">
                                    <div id="jsGrid">
    <table>
       <tbody>
            <tr class="jsgrid-header-row">
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 300px;"></th>
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 300px;">image_id</th>
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 300px;">name</th>
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 300px;">create_date</th>
            <th class="jsgrid-header-cell jsgrid-control-field jsgrid-align-center" style="width: 300px;">
                    <a href="?action=create"><span class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button ti-plus" type="button" title=""></span></a>
            </th> 
        </tr>
    ';

    foreach ($imagesCollection as $imageItem) {
        echo '<tr class="jsgrid-row" style="display: table-row;">';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 150px;">';
        echo '<img id="imageTab" src="' . getImage($imageItem->image_id) . '" alt="Image" title="' . $imageItem->name . '">' . PHP_EOL;

        echo '</td>';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 150px;">' . $imageItem->image_id . '</td>';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 150px;">' . $imageItem->name . '</td>';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 150px;">' . $imageItem->create_date . '</td>';
        echo '<td class="jsgrid-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;"> 
                <a href="?action=update&image_id=' . $imageItem->image_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a> 
                <a href="?action=delete&image_id=' . $imageItem->image_id . '"><span class="jsgrid-button jsgrid-delete-button ti-trash" type="button" title="Delete"></span></a> 
                </td>';
        echo '</tr>';

    }


    echo '</tbody>
    </table>';
}
echo '</div>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->

                    
                </div>';

function getImage(string $id)
{
    $imageDirectory = '../assets/images/upload/';

    foreach (['jpg', 'jpeg', 'png'] as $extension) {
        if (file_exists($imageDirectory . $id . '.' . $extension)) {
            return $imageDirectory . $id . '.' . $extension;
        }
    }
    return $imageDirectory . "default.png";
}




echo '</div>';
require_once '../footer.php';


?>



