<?php
require_once '../header.php';
require_once "../model/profiles.php";


if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
}
$displayForm = false;
$displayFormImage = false;

if (isset($action)) {
    if ($action == "update") {
        if (isset($_POST['submit'])) {
            $userId = $_POST['user_id'];
            $nom = $_POST['name'];
            $prenom = $_POST['firstName'];
            $phone = $_POST['phone'];
            $adresse = $_POST['address'];
            $email = $_POST['email'];
            $ville = $_POST['city'];
            $imageId = $_POST['image_id'];

            $sql = "UPDATE profile SET name=:name, firstName=:firstName, phone=:phone, address=:address, email=:email, city=:city, image_id=:imageId WHERE user_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $userId, PDO::PARAM_INT);
                $statement->bindParam(':name', $nom, PDO::PARAM_STR);
                $statement->bindParam(':firstName', $prenom, PDO::PARAM_STR);
                $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
                $statement->bindParam(':address', $adresse, PDO::PARAM_STR);
                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->bindParam(':city', $ville, PDO::PARAM_STR);
                $statement->bindParam(':imageId', $imageId, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        } else {
            $displayForm = true;
        }
    } /*else if ($action == "updateImage") {
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

                    $sqlDeleteImageId = "UPDATE profile SET image_id=NULL WHERE user_id=:userId ;";
                    //$sqlDeleteImage = "DELETE FROM images_profile WHERE user_id=:userId;";
                    $sql = "INSERT INTO images_profile(user_id) VALUES (:userId);";
                    $sql2 = "UPDATE profile JOIN images_profile ON images_profile.user_id = profile.user_id SET profile.image_id = images_profile.image_id WHERE profile.user_id = :userId;";

                    try {
                        global $pdo;
                        $stat = $pdo->prepare($sqlDeleteImageId);
                        $stat->bindParam(':userId', $userId, PDO::PARAM_INT);
                        $stat->execute();

                    } catch (PDOException $e) {
                        echo 'Errrrrreur : ' . $e->getMessage();
                    }


                    try {
                        global $pdo;
                        $stat = $pdo->prepare($sqlDeleteImage);
                        $stat->bindParam(':userId', $userId, PDO::PARAM_INT);
                        $stat->execute();

                    } catch (PDOException $e) {
                        echo 'Errrrrreur : ' . $e->getMessage();
                    }



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

                    try {
                        global $pdo;
                        $statement = $pdo->prepare($sql2);
                        $statement->bindParam(':userId', $userId, PDO::PARAM_INT);
                        $statement->execute();

                    } catch (PDOException $e) {
                        echo 'laaaa : ' . $e->getMessage();
                    }
                } else {
                    echo "Une erreur s'est produite lors du téléchargement de l'image.";
                }
            } else {
                echo "Le format de fichier n'est pas pris en charge. Veuillez sélectionner une image valide.";
            }
        } else {
            $displayFormImage = true;
        }
    }
 */

}
$profileUserData = getProfileById($_SESSION['user_id']);

$profileImagePath = ($profileUserData->image_id ? '../assets/images/profiles/' . $profileUserData->user_id . '/' . $profileUserData->image_id . '.jpg' : "../assets/images/user-profile.jpg");


echo '<div class="container">';
$images = getImagesProfileById($profileUserData->user_id);
//echo "<pre>" . print_r($images, true) . "</pre>";

if ($displayForm) {
    echo '
        <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Update Profile</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
        
    <form class="form-valide" name="profileForm" method="POST" action="?action=' . $action . '" onsubmit="return validateEmail()"  >
     
       
        
        
        Gestion Image Profile: <a href="gestionImageProfile.php?user_id=' . $profileUserData->user_id . '"><button type="button" class="btn btn-primary btn-sm btn-addon m-b-5 m-l-5"><i class="ti-plus"></i>Add Photo</button>
</a>
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="name">Nom :</label>
        <div class="col-lg-9">
          <input class="form-control" type="text" name="name" value="' . ($action == 'update' ? $profileUserData->name : '') . '" autocomplete="off">
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="firstName">Prenom :</label>
        <div class="col-lg-9">
        <input class="form-control" type="text" name="firstName" value="' . ($action == 'update' ? $profileUserData->firstName : '') . '" autocomplete="off">
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="phone">Phone :</label>
                    <div class="col-lg-9">
        <input class="form-control" type="text" name="phone" value="' . ($action == 'update' ? $profileUserData->phone : '') . '" autocomplete="off">
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="address">Adresse :</label>
                    <div class="col-lg-9">
        <input class="form-control" type="text" name="address" value="' . ($action == 'update' ? $profileUserData->address : '') . '" autocomplete="off">
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="email">Email :</label>
                    <div class="col-lg-9">
         <input class="form-control" type="text" id="email" name="email"  value="' . ($action == 'update' ? $profileUserData->email : '') . '" autocomplete="off">
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label" for="city">Ville :</label>
                    <div class="col-lg-9">
        <input class="form-control" type="text" name="city" value="' . ($action == 'update' ? $profileUserData->city : '') . '" autocomplete="off" >
        </div>
        </div>
        
        <br>';


    if (!empty($images)) {
        echo '
        <div class="form-group row ">                             
            <label class="col-lg-3 col-form-label">ImageId:</label>
             <div class="col-lg-9">';

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
                
        <input type="radio" id="imageRadio" name="image_id" value="' . $image->image_id . '">
        </div>';
        }

// Fermer la dernière ligne
        echo '</div>';

        echo '
        </div>
        </div>
        </div>
                
            
            <br>';
        echo '<div id="test" >
            
    </div>';


    }
    echo '
        <input type="hidden" id="user_id" name="user_id" value="' . ($action == 'update' ? $id : '') . '">
        
        <a class="btn btn-default btn-flat btn-addon m-b-10 m-l-5" href="profiles.php"><i class="ti-back-left"></i></span>Retour</a>

        <button type="submit" name="submit"  class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>

    </form>';

} elseif ($displayFormImage) {
    echo '
    <form name="imageForm" action="?action=' . $action . '" method="POST" enctype="multipart/form-data" >
        <input type="file" name="image" >
        <input type="hidden" name="user_id" value="' . ($action == 'updateImage' ? $id : '') . '">
        <input type="hidden" name="image_id" value="' . ($action == 'updateImage' ? $profileUserData->image_id : '') . '">
        <a class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" href="?action=update&user_id=' . $id . '"><i class="ti-back-left"></i></span>Retour</a>
        <button type="submit" name="submit" class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>
    </form>
    </div>
    </div>
            </div>
            </div>
            </div>';


} else {
    echo '

<div class="content-wrap">
    <div class="main">
        <div class="container-fluid">
            <section id="main-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="user-profile">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="user-photo m-b-30">
                                                 <img class="img-fluid" src="' . $profileImagePath . '" alt=""/>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="user-profile-name">' . $_SESSION['login'] . '</div>
                                            <div class="user-Location">
                                                <i class="ti-location-pin">' . $profileUserData->city . '</i>  
                                            </div>
                                            <div class="user-job-title">Product Designer</div>
                                            
                                            <div class="user-send-message">
                                                <button class="btn btn-primary btn-addon" type="button">
                                                    <i class="ti-email"></i>Send Message
                                                </button>
                                            </div>
                                            <div class="custom-tab user-profile-tab">
                                                <ul class="nav nav-tabs" role="tablist">
                                                    <li role="presentation" class="active">
                                                        <a href="#1" aria-controls="1" role="tab" data-toggle="tab">About</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div role="tabpanel" class="tab-pane active" id="1">
                                                        <div class="contact-information">
                                                            <h4>Contact information   <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a></h4>
                                                            
                                                            
                                                            <div class="name-content">
                                                                <span class="contact-title">Name:</span>
                                                                <span class="phone-number">' . $profileUserData->name . '</span> 

                                                            </div>
                                                            <div class="name-content">
                                                                <span class="contact-title">First Name:</span>
                                                                <span class="phone-number">' . $profileUserData->firstName . '</span>
                                                            </div>
                                                            <div class="phone-content">
                                                                <span class="contact-title">Phone:</span>
                                                                <span class="phone-number">' . $profileUserData->phone . '</span>
                                                            </div>
                                                            <div class="address-content">
                                                                <span class="contact-title">Address:</span>
                                                                <span class="mail-address">' . $profileUserData->address . '</span>
                                                            </div>
                                                            <div class="email-content">
                                                                <span class="contact-title">Email:</span>
                                                                <span class="contact-email">' . $profileUserData->email . '</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </section>
        </div>
    </div>
</div>
';
}

echo '</div>';
require_once '../footer.php';
?>