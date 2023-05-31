<?php
require_once "../model/users.php";
require_once "../model/types.php";
require_once '../header.php';
require_once '../footer.php';

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

            $sql = "INSERT INTO users(login, password, type_id) VALUES (:login, :password, :typeId);";
            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                var_dump($statement);
                $statement->bindParam(':login', $login, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->bindParam(':typeId', $typeId, PDO::PARAM_INT);
                $statement->execute();
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
                var_dump($statement);
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

$users = getAllUsers();
$typeCollection = getAllTypes();


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
    </form>
    ';
} else {
    echo '
    <table>
        <tr>
            <th>user_id</th>
            <th>Login</th>
            <th>Password</th>
            <th>type id</th>
            <th>role</th>
            <th>date</th>
            <th>action</th>
            <th>supprimer</th>
        </tr>
    ';



    foreach ($users as $user) {
        echo '<tr>';
        echo '<td>' . $user->user_id . '</td>';
        echo '<td>' . $user->login . '</td>';
        echo '<td>' . $user->password . '</td>';
        echo '<td>' . $user->type_id . '</td>';
        $role = getRole($user->type_id);
        //echo "<pre>" . print_r($role, true) . "</pre>";
        echo '<td>' . $role[0]->role . '</td>';
        echo '<td>' . $user->create_date . '</td>';
        echo '<td> <a href="?action=update&user_id=' . $user->user_id . '">edit</a> </td>';
        echo '<td> <a href="?action=delete&user_id=' . $user->user_id . '">delete</a> </td>';
        echo '</tr>';
    }

    echo '</table>';
}

echo '<a href="?action=create">Create</a>';

?>









