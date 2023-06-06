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

    }


}

$users = getAllUsers();
$typeCollection = getAllTypes();

echo '<div class="container">';

if ($displayForm) {
    echo '
    <form name="userForm" method="POST" action="?action=' . $action . '" onsubmit= "return validateForm2(\'userForm\',\'login\', \'password\'); " onkeypress="verifierCaracteres(event); return false;">
        Login : <input type="text" name="login"  value="' . ($action == 'update' ? $userInfo->login : '') . '" />
        <br>
        Password : <input type="password" name="password"  value="' . ($action == 'update' ? $userInfo->password : '') . '">
        <br>
        <input type="hidden" name="user_id" value="' . ($action == 'update' ? $id : '' ) . '">
        Role:
            <select name="type_id">';
        foreach ($typeCollection as $type){
            echo '<option value ="' . $type->type_id . '"' . ( $action == 'update'   ? $type->role  : '' ) . ' >' . $type->role .'</option>';
        }

        echo '</select>
        
        <input type="submit" name="submit" value="submit">
        <a href="users.php">Retour</a>
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
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">user_id</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">Login</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">Password</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">type id</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">role</th>
                <th class="jsgrid-header-cell jsgrid-align-center" style="width: 200px;">date</th>
                <th class="jsgrid-header-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;">
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



echo '<a class="create" href="?action=create">Create</a>';
echo '</div>';
require_once '../footer.php';

?>


<!--<link href="../assets/css/users.css" rel="stylesheet"> -->









