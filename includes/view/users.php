<?php
require_once '../header.php';
require_once "../model/users.php";
require_once "../model/types.php";


//var_dump($_POST);

//echo "<pre>" . print_r($_GET, true) . "</pre>";
//echo "<pre>" . print_r($_POST, true) . "</pre>";


$displayForm = false;

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['user_id'])) {
    $id = $_GET['user_id'];
}


if (isset($action)) {
    if ($action == "create") {
        $displayForm = true;
        // si le formulaire a été submit
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $typeId = $_POST['type_id'];
            //$idUser = $_POST['user_id'];

            $sql = "INSERT INTO users(login, password, type_id) VALUES (:login, :password, :typeId);";
            $sql2 = "INSERT INTO profile(user_id) VALUES (:id);";
            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                //var_dump($statement);
                $statement->bindParam(':login', $login, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->bindParam(':typeId', $typeId, PDO::PARAM_INT);
                $statement->execute();
                $newUserID = $pdo->lastInsertId();


            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }

            try {
                global $pdo;
                $stat = $pdo->prepare($sql2);
                $stat->bindParam(':id', $newUserID, PDO::PARAM_INT);
                $stat->execute();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }

            // on retire le formulaire
            $displayForm = false;

        }
    } else if ($action == "update") {
        // si le formulaire a été submit
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $id = $_POST['user_id'];

            $typeId = $_POST['type_id'];


            $sql = "UPDATE users SET login=:login, password=:password, type_id=:type_id WHERE user_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':login', $login, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->bindParam(':type_id', $typeId, PDO::PARAM_INT);
                $statement->execute();


            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

        } else {
            $displayForm = true;
            $userInfo = getUser($id);
        }
    } else if ($action == "delete" && !empty($id)) {

        $sql = "DELETE FROM users WHERE user_id = :id";
        $sq2 = "DELETE FROM profile WHERE user_id = :id";

        try {
            global $pdo;
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $stat = $pdo->prepare($sq2);
            $stat->bindParam(':id', $id, PDO::PARAM_INT);
            $stat->execute();

        } catch (PDOException $e) {
            die("erreur dans la requete " . $e->getMessage());
        }
        $imagePath = '../assets/images/profiles/' . $id;
        echo "<pre>" . print_r($imagePath, true) . "</pre>";

        if (is_dir($imagePath)) {
            $files = array_diff(scandir($imagePath), array('.', '..')); // Récupère tous les fichiers et dossiers dans le répertoire, en excluant les liens "." et ".."

            foreach ($files as $file) {
                if (is_dir("$imagePath/$file")) {
                    deleteDirectory("$imagePath/$file"); // Appel récursif pour supprimer les sous-dossiers
                } else {
                    unlink("$imagePath/$file"); // Supprime les fichiers
                }
            }

            rmdir($imagePath); // Supprime le dossier
            echo "Le dossier a été supprimé avec succès.";
        } else {
            echo "Le dossier spécifié n'existe pas.";
        }
    }

}


$users = getAllUsers();
$typeCollection = getAllTypes();

echo '<div class="container">';

if ($displayForm) {
    echo '
<section id="main-content">
    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Formulaire</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
                                        
                                                
                                                    
    <form class="form-valide" name="userForm" method="POST" action="?action=' . $action . '"  >
    
    <div class="form-group row ">                             
        <label class="col-lg-3 col-form-label" for="login">Login :<span class="text-danger">*</span></label>
        <div class="col-lg-9">
         <input class="form-control" type="text" id="login" name="login"  value="' . ($action == 'update' ? $userInfo->login : '') . '" onkeypress="verifierCaracteres(event); return false;"/>
         </div>
    </div>
         
        <br>
        
        <div class="form-group row ">                             
        <label class="col-lg-3 col-form-label" for="password">Password :<span class="text-danger">*</span></label>
        <div class="col-lg-9">
        <input class="form-control" id="password" type="password" name="password"  value="' . ($action == 'update' ? $userInfo->password : '') . '">
        </div>
        </div>
        
        <br>
        
        <input type="hidden" name="user_id" value="' . ($action == 'update' ? $id : '') . '">
        
       
               <div class="form-group row ">                             

        <label class="col-lg-3 col-form-label"for="role">Role :<span class="text-danger">*</span></label>
        <div class="col-lg-9">
            <select class="form-control" name="type_id">';
    foreach ($typeCollection as $type) {
        echo '<option value ="' . $type->type_id . '"' . ($action == 'update' ? $type->role : '') . ' >' . $type->role . '</option>';
    }

    echo '</select>
           
        </div>
        </div>
        <br>
      
        <a class="btn btn-info btn-flat btn-addon m-b-10 m-l-5" href="users.php"><i class="ti-back-left"></i></span>Retour</a>
                   
        <button type="submit" name="submit"  class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>
                                        
    </form>
    </div>
    </div>
            </div>
            </div>
            </div>
            </section>
            
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
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">user_id</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">Login</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">Password</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">type id</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">role</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 200px;">date</th>
                <th class="jsgrid-header-cell jsgrid-control-field jsgrid-align-center" style="width: 100px;">
                    <a href="?action=create"><span class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button ti-plus" type="button" title=""></span></a>
                </th>            
            </tr>
        
    ';


    foreach ($users as $user) {
        if ($user->user_id != $_SESSION['user_id']) {
            echo '<tr class="jsgrid-row" style="display: table-row;">';
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 150px;">' . $user->user_id . '</td>';
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $user->login . '</td>';
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $user->password . '</td>';
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $user->type_id . '</td>';
            $role = getRole($user->type_id);
            //echo "<pre>" . print_r($role, true) . "</pre>";
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $role[0]->role . '</td>';
            echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $user->create_date . '</td>';
            echo '<td class="jsgrid-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;"> 
                <a href="?action=update&user_id=' . $user->user_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a> 
                <a href="?action=delete&user_id=' . $user->user_id . '"><span class="jsgrid-button jsgrid-delete-button ti-trash" type="button" title="Delete"></span></a> 
                </td>';
            echo '</tr>';
        }
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


echo '</div>';
require_once '../footer.php';

?>


<!--<link href="../assets/css/users.css" rel="stylesheet"> -->









