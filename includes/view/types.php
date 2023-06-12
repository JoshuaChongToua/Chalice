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
    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Add Type</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
    
    <form class="form-valide" name="typeForm" method="POST" action="?action=' . $action . '"  onkeypress="verifierCaracteres(event); return false;">
        
        <div class="form-group row ">                             
         <label class="col-lg-3 col-form-label" for="login">Role :<span class="text-danger">*</span></label>
         <div class="col-lg-9">
        <input class="form-control" type="text" name="role" value="' . ($action == 'update' ? $typeInfo->role : '') . '"  />
        </div>
    </div>
    
        <br>
        
        <input type="hidden" name="type_id" value="' . ($action == 'update' ? $id : '' ) . '">
        <br>
        <a class="btn btn-default btn-flat btn-addon m-b-10 m-l-5" href="types.php"><i class="ti-back-left"></i></span>Retour</a>
        <button type="submit" name="submit"  class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>

    </form>
     </div>
    </div>
            </div>
            </div>
            </div>
    ';
} else {
    echo '
<div id="main-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
    <table class="table table-striped">
        <thead>

           <tr class="jsgrid-align-center">
            <th style="width: 400px;">#</th>
            <th style="width: 400px;">Role</th>
            <th style="width: 400px;">
                 <a href="?action=create"><span class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button ti-plus" type="button" title=""></span></a>
            </th>
        </tr>
         </thead>
 <tbody> ';

    foreach ($types as $type) {
        echo '<tr class="jsgrid-align-center" style="display: table-row;">';
        echo '<td style="width: 100px;">' . $type->type_id . '</td>';
        echo '<td style="width: 100px;">' . $type->role . '</td>';
        echo '<td style="width: 50px;"> 
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

echo '</div>';
require_once '../footer.php';


?>
