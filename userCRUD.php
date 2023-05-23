<?php
require_once "includes/Users.php";
var_dump($_POST);

echo "<pre>".print_r( $_GET, true)."</pre>";


$displayForm = false;

if(isset($_GET['action'])){
    $action = $_GET['action'];
}

if(isset($_GET['user_id'])){
    $id = $_GET['user_id'];
}


if(isset($action)) {
    if ($action == "create") {
        $displayForm = true;
        // si le formulaire a été submit
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $sql = "INSERT INTO users(login, password, type_id) VALUES (:login, :password, 2);";
            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':login', $login, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            // on retire le formulaire
            $displayForm = false;

        }
    } else if ($action == "update" && !empty($id)) {
        $displayForm = true;

        $infoUser = getUser($id);

        // si le formulaire a été submit
        if (isset($_POST['login']) && isset($_POST['password'])) {
            $login = $_POST['login'];
            $password = $_POST['password'];



            $sql = "UPDATE users SET login=:login, password=:password, type_id=:type_id WHERE id=:id;";
            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':login', $login, PDO::PARAM_STR);
                $statement->bindParam(':password', $password, PDO::PARAM_STR);
                //$statement->bindParam(':type_id', $type_id, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
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
}


    $users = getAllUsers();


    if ($displayForm) {
        echo '
    <form method="POST" action="?action=' . $action . '">
        Login : <input type="text" name="login"  value="' . ($action == 'update' ? $infoUser->login : '') . '" />
        <br>
        Password : <input type="password" name="password"  value="' . ($action == 'update' ? $infoUser->password : '') . '">
        <br>
        <!--Role:
        <select name="role">
        <option value ="admin" >admin</option>
        <option value="visiteur">visiteur</option>
        </select>
        
        <input type="submit" name="submit" value="submit">
    </form> -->
    ';
    } else {
        echo '
    <table>
        <tr>
            <th>user_id</th>
            <th>Login</th>
            <th>Password</th>
            <th>type id</th>
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
            echo '<td>' . $user->create_date . '</td>';
            echo '<td> <a href="?action=update&user_id=' . $user->user_id . '">edit</a> </td>';
            echo '<td> <a href="?action=delete&user_id=' . $user->user_id . '">delete</a> </td>';
            echo '</tr>';
        }

        echo '</table>';
    }

    echo '<a href="?action=create">Create</a>' ;
?>


<link href="css/userCrud.css" rel="stylesheet" media="screen">








