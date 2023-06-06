<?php
require_once '../header.php';
require_once "../model/profile.php";


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
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $phone = $_POST['phone'];
            $adresse = $_POST['adresse'];
            $email = $_POST['email'];
            $ville = $_POST['ville'];

            $sql = "UPDATE profile SET nom=:nom, prenom=:prenom, phone=:phone, adresse=:adresse, email=:email, ville=:ville WHERE user_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $userId, PDO::PARAM_INT);
                $statement->bindParam(':nom', $nom, PDO::PARAM_STR);
                $statement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
                $statement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $statement->bindParam(':email', $email, PDO::PARAM_STR);
                $statement->bindParam(':ville', $ville, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }
        } else {
            $displayForm = true;
        }
    } else if ($action == "updateImage") {
        if (isset($_POST['submit'])) {
            $userId = $_POST['user_id'];
            $imageId = $_POST['image_id'];
            echo "<pre>" . print_r($_POST, true) . "</pre>";

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
                    $sql2 = "UPDATE profile SET image_id=:imageID WHERE user_id=:id;";
                    try {
                        global $pdo;
                        $stat = $pdo->prepare($sql);
                        $stat->bindParam(':userId', $userId, PDO::PARAM_INT);
                        $stat->execute();

                        $destination2 = '../assets/images/profiles/' . $userId . '/' . $pdo->lastInsertId() . '.' . $fileExtension;

                        if (rename($destination, $destination2)) {
                            echo 'Fichier bien renomee';
                        } else {
                            echo 'echec';
                        }
                        $displayFormImage = false;
                    } catch (PDOException $e) {
                        echo 'pasla : ' . $e->getMessage();
                    }

                    try {
                        global $pdo;
                        $statement = $pdo->prepare($sql2);
                        $statement->bindParam(':id', $userId, PDO::PARAM_INT);
                        $statement->bindParam(':imageId', $imageId, PDO::PARAM_INT);
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
}
    $profileUserData = getProfileById($_SESSION['user_id']);

    echo '<div class="container">';


    if ($displayForm) {
        echo '
    <form name="profileForm" method="POST" action="?action=' . $action . '" onsubmit="return validateEmail()"  >
        Nom : <input type="text" name="nom" value="' . ($action == 'update' ? $profileUserData->nom : '') . '" autocomplete="off">
        <br>
        Prenom : <input type="text" name="prenom" value="' . ($action == 'update' ? $profileUserData->prenom : '') . '" autocomplete="off">
        <br>
        Phone : <input type="text" name="phone" value="' . ($action == 'update' ? $profileUserData->phone : '') . '" autocomplete="off">
        <br>
        Adresse : <input type="text" name="adresse" value="' . ($action == 'update' ? $profileUserData->adresse : '') . '" autocomplete="off">
        <br>
        Email : <input type="text" id="email" name="email"  value="' . ($action == 'update' ? $profileUserData->email : '') . '" autocomplete="off">
        <br>
        Ville : <input type="text" name="ville" value="' . ($action == 'update' ? $profileUserData->ville : '') . '" autocomplete="off" >
        <br>
        <input type="hidden" name="user_id" value="' . ($action == 'update' ? $id : '') . '">
        <input type="submit" name="submit" value="submit" ">
        <a href="profile.php">Retour</a>
    </form>';

    } elseif ($displayFormImage) {
        echo '
    <form name="imageForm" action="?action=' . $action . '" method="POST" enctype="multipart/form-data" >
        <input type="file" name="image" >
        <input type="hidden" name="user_id" value="' . ($action == 'updateImage' ? $id : '') . '">
        <input type="hidden" name="image_id" value="' . ($action == 'updateImage' ? $profileUserData->image_id : '') . '">
        <input type="submit" name="submit" value="submit" ">
    </form>';


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
                                                <a href="?action=updateImage&user_id=' . $profileUserData->user_id . '"> <img class="img-fluid" src="' . ($profileUserData->image_id ?? "../assets/images/user-profile.jpg") . '" alt=""/></a>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="user-profile-name">' . $_SESSION['login'] . '</div>
                                            <div class="user-Location">
                                                <i class="ti-location-pin">' . $profileUserData->ville . '</i>  
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
                                                            <h4>Contact information</h4>
                                                            <div class="name-content">
                                                                <span class="contact-title">Nom:</span>
                                                                <span class="phone-number">' . $profileUserData->nom . '</span> 
                                                                <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a> 

                                                            </div>
                                                            <div class="name-content">
                                                                <span class="contact-title">Prenom:</span>
                                                                <span class="phone-number">' . $profileUserData->prenom . '</span>
                                                                <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a>
                                                            </div>
                                                            <div class="phone-content">
                                                                <span class="contact-title">Phone:</span>
                                                                <span class="phone-number">' . $profileUserData->phone . '</span>
                                                                <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a>
                                                            </div>
                                                            <div class="address-content">
                                                                <span class="contact-title">Address:</span>
                                                                <span class="mail-address">' . $profileUserData->adresse . '</span>
                                                                <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a>
                                                            </div>
                                                            <div class="email-content">
                                                                <span class="contact-title">Email:</span>
                                                                <span class="contact-email">' . $profileUserData->email . '</span>
                                                                <a href="?action=update&user_id=' . $profileUserData->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a>
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