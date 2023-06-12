<?php
require_once '../header.php';
require_once "../model/profiles.php";
require_once "../model/images.php";


if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
}
if (isset($_GET['image_id'])) {
    $idImage = $_GET['image_id'];
}

if ($action == "delete" && isset($idImage)) {
    $imageDataById = getImageProfileById($idImage);

    if ($imageDataById) {
        $idUser = $_SESSION['user_id'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $filename = $imageDataById->image_id;
        $fileCollection = glob('../assets/images/profiles/' . $idUser . '/' . $filename . '.*');
        $tempPath = '../assets/images/profiles/' . $idUser . '/' . $filename;
        //echo $tempPath;
        foreach ($fileCollection as $filePath) {
            //echo "<pre>" . print_r($filePath, true) . "</pre>";
            foreach ($allowedExtensions as $extension) {

                if ($tempPath . '.' . $extension == $filePath) {
                    $filename = $imageDataById->image_id . '.' . $extension;
                    //echo "<pre>" . print_r($filename, true) . "</pre>";
                }

            }
        }

        $fileExtension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $imagePath = '../assets/images/profiles/' . $idUser . '/' . $filename;
        //echo "<pre>" . print_r($imagePath, true) . "</pre>";

        if (file_exists($imagePath)) {
            // Supprimer l'image du dossier
            unlink($imagePath);
            // Supprimer l'entrée correspondante dans la base de données
            deleteImageProfileById($idImage);
            echo "L'image et l'entrée correspondante ont été supprimées avec succès.";
        } else {
            echo "L'image n'a pas été trouvée dans le dossier.";
        }
    } else {
        echo "L'image n'a pas été trouvée dans la base de données.";
    }
}



if (isset($_POST['submit'])) {
    $userId = $_POST['user_id'];
    $imageId = $_POST['image_id'];
    //echo "<pre>" . print_r($_POST, true) . "</pre>";

    $file = $_FILES['image'];
    $tmpFilePath = $file['tmp_name'];
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $destination = '../assets/images/profiles/' . $userId . '/' . kodex_random_string($length = 10) . '.' . $fileExtension;

    if (in_array($fileExtension, $allowedExtensions)) {
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($tmpFilePath, $destination)) {
            // Le fichier a été téléchargé avec succès, vous pouvez effectuer d'autres actions si nécessaire
            echo "L'image a été téléchargée avec succès.";

            $sql = "INSERT INTO images_profile(user_id) VALUES (:userId);";

            try {
                global $pdo;
                $stat = $pdo->prepare($sql);
                $stat->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stat->execute();

                $destination2 = '../assets/images/profiles/' . $userId . '/' . $pdo->lastInsertId() . '.' . $fileExtension;

                rename($destination, $destination2);

                $displayFormImage = false;
            } catch (PDOException $e) {
                echo 'pasla : ' . $e->getMessage();
            }

        } else {
            echo "Une erreur s'est produite lors du téléchargement de l'image.";
        }
    } else {
        echo "Le format de fichier n'est pas pris en charge. Veuillez sélectionner une image valide.";
    }
}

echo '<div class="container">';

$imagesCollection = getImagesProfile();
$profileUserData = getProfileById($_SESSION['user_id']);
$images = getImagesProfileById($profileUserData->user_id);


//echo "<pre>" . print_r($_POST, true) . "</pre>";
//echo "<pre>" . print_r($profileUserData, true) . "</pre>";




echo '
            <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Add Image</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
<form class="form-valide" name="imageForm" action="?action=' . $action . '" method="POST" enctype="multipart/form-data" >
        <div class="form-group row ">  
             <div class="col-lg-8">
                <input class="form-control" type="file" name="image" >
                </div>
                </div>
                
        <input type="hidden" name="user_id" value="' . $profileUserData->user_id . '">
        <input type="hidden" name="image_id" value="' . $profileUserData->image_id . '">
        <a class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" href="profiles.php?action=update&user_id=' . $profileUserData->user_id . '"><i class="ti-back-left"></i></span>Retour</a>
        <button type="submit" name="submit" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>
    </form>
    </div>
    </div>
            </div>
            </div>
            </div>';

echo '
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-title">
                <h4>Update Profile</h4>
            </div>
            <div class="card-body">
                <div class="form-validation">
                    <div class="form-group row">
                        <div class="col-lg-12">';

$imagesPerRow = 6;
$imageCount = count($images);

for ($i = 0; $i < $imageCount; $i++) {
    $image = $images[$i];
    $selected = ($image->image_id == $profileUserData->image_id) ? 'selected="selected"' : '';

    if ($i % $imagesPerRow == 0) {
        if ($i != 0) {
            echo '</div>';
        }

        echo '<div class="row">';
    }


    echo '
        <div class="col-lg-2">
                <img id="imageTab" src="../assets/images/profiles/' . $profileUserData->user_id . '/' . $image->image_id . '.jpg">
                
                <a href="?action=delete&image_id=' . $image->image_id . '"><span class="jsgrid-button jsgrid-delete-button ti-trash" type="button" title="Delete"></span></a> 
        </div>';
}

// Fermer la dernière ligne
echo '</div>';

echo '
        </div>
    </div>
</div>';


require_once '../footer.php';
?>