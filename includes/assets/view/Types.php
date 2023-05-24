<?php
require_once '../../model/Types.php';
require_once '../../header.php';
require_once '../../footer.php';
$displayForm = false;

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['type_id'])) {
    $id = $_GET['type_id'];
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
    } else if ($action == "update") {
        // si le formulaire a été submit
        if (isset($_POST['role'])) {
            $role = $_POST['role'];
            $id = $_POST['type_id'];

            $sql = "UPDATE users_types SET role=:role WHERE type_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':role', $role, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

        } else {
            $displayForm = true;
            $infoType = get_Type($id);
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
<html>
<head>
    <title>Type</title>
    <script type="text/javascript">
        function validateForm(form_name,val_name)
        {
            var x = document.forms[form_name][val_name].value;

            if (x == "") {
                alert("Login must be filled out");
                return false;
            }

        }
    </script>
</head>
<body>


<?php
$types = getAllTypes();

if ($displayForm) {

    echo '
    
    <form name="typeForm" method="POST" action="?action=' . $action . '" onsubmit= "return validateForm(\'typeForm\',\'role\'); " required>
        Role : <input type="text" name="role" value="' . ($action == 'update' ? $infoType->role : '') . '"  />
        <br>
        <input type="hidden" name="type_id" value="' . ($action == 'update' ? $id : '' ) . '">
        <input type="submit" name="submit" value="submit">
    </form>
    ';
} else {
    echo '
    <table>
        <tr>
            <th>type_id</th>
            <th>role</th>
        </tr>
    ';

    foreach ($types as $type) {
        echo '<tr>';
        echo '<td>' . $type->type_id . '</td>';
        echo '<td>' . $type->role . '</td>';
        echo '<td> <a href="?action=update&type_id=' . $type->type_id . '">edit</a> </td>';
        echo '<td> <a href="?action=delete&type_id=' . $type->type_id . '">delete</a> </td>';
        echo '</tr>';
    }

    echo '</table>';
}

echo '<a href="?action=create">Create</a>';
echo '<br>';
echo '<a href="Users.php">UserCRUD</a>';
echo '<br>';
echo '<a href="News.php">newsCrud</a>';
?>
</body>


<link href="../css/userCrud.css" rel="stylesheet" media="screen">