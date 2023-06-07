<?php
require_once '../header.php';
require_once '../model/types.php';
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
            $typeInfo = getTypeById($id);
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




<?php
$types = getAllTypes();
echo '<div class="container">';


if ($displayForm) {

    echo '
    
    <form name="typeForm" method="POST" action="?action=' . $action . '" onsubmit= "return validateForm(\'typeForm\',\'role\'); " onkeypress="verifierCaracteres(event); return false;">
        Role : <input type="text" name="role" value="' . ($action == 'update' ? $typeInfo->role : '') . '"  />
        <br>
        <input type="hidden" name="type_id" value="' . ($action == 'update' ? $id : '' ) . '">
        <input type="submit" name="submit" value="submit">
        <a href="types.php">Retour</a>

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
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">type_id</th>
            <th class="jsgrid-header-cell jsgrid-align-center" style="width: 150px;">role</th>
            <th class="jsgrid-header-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;">
                    <a href="?action=create"><span class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button ti-plus" type="button" title=""></span></a>
            </th>
        </tr>
    ';

    foreach ($types as $type) {
        echo '<tr class="jsgrid-row" style="display: table-row;">';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $type->type_id . '</td>';
        echo '<td class="jsgrid-cell jsgrid-align-center" style="width: 100px;">' . $type->role . '</td>';
        echo '<td class="jsgrid-cell jsgrid-control-field jsgrid-align-center" style="width: 50px;"> 
                <a href="?action=update&type_id=' . $type->type_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a> 
                <a href="?action=delete&type_id=' . $type->type_id . '"><span class="jsgrid-button jsgrid-delete-button ti-trash" type="button" title="Delete"></span></a> 
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

echo '<a class="create" href="?action=create">Create</a>';
echo '</div>';
require_once '../footer.php';


?>
