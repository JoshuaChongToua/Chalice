<?php
require_once "includes/Users.php";
?>
<link href="css/userCrud.css" rel="stylesheet" media="screen">
<?php
$users = getAllUsers();

$displayList = true;
$displayForm = false;





if(isset($_GET['action'])) {
    if ($_GET['action'] == "create") {
        $displayList = false;
        $displayForm = true;
        if (isset($_GET['login'])&& !empty($_GET['login'])) {
            $login = $_GET['login'];
        }
        if (isset($_GET['password'])&& !empty($_GET['password'])) {
            $password = $_GET['password'];
        }

        $sql = "INSERT into users(login, password, type_id) values(:login, :password,2);";
        try {
            global $pdo;
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':login', $login, PDO::PARAM_STR);
            $statement->bindParam(':password', $password, PDO::PARAM_STR);
            $statement->execute();
        }
        catch (PDOException $e){
            echo "Erreur : ".$e->getMessage();
        }

    }


    if ($_GET['action'] == "read") {
        $displayList = true;
        $displayForm = false;
    }

    if ($_GET['action']=="update") :
        $displayList = false;
        $displayForm = false;
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])){
            $id = $_GET['user_id'];
        }
        $infoUser = getUser($id);

        ?>
        <form method="GET" >
            Login : <input type="text" name="login"  value="<?php echo $infoUser->login; ?>" />
        <br>
        Password : <input type="password" name="password"  value="<?php echo $infoUser->password; ?>">
        <input type="submit" name="submit" value="submit">
    </form>
    <?php
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])){
        $id = $_GET['user_id'];
        }


        if (isset($_GET['login']) && !empty($_GET['login'])){
        $login = $_GET['login'];
        }
        if (isset($_GET['password']) && !empty($_GET['password'])){
        $password = $_GET['password'];
        }
        $sql = "Update users set login=:login, password=:password where id=:id;";
        try {
        global $pdo;
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->bindParam(':login', $login, PDO::PARAM_STR);
        $statement->bindParam(':password', $password, PDO::PARAM_STR);
        $statement->execute();
        }
        catch (PDOException $e){
        echo 'Erreur : '.$e->getMessage();}

     endif;


    if ($_GET['action'] == "delete") {
        $displayList = true;
        $displayForm = false;
        if (isset($_GET['user_id']) && !empty($_GET['user_id'])){
            $id = $_GET['user_id'];
        }

        $sql = "DELETE FROM users where user_id = :id";
        try {
            global $pdo;
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            die("erreur dans la requete " . $e->getMessage());
        }

    }


}


/*create*/







/*delete*/



/*update*/














if($displayForm): ?>
    <form method="GET" >
        Login : <input type="text" name="login" >
        Password : <input type="password" name="password" >
        <input type="submit" name="submit" value="submit">
    </form>
<?php endif;



if($displayList):
    ?>
    <table>
    <tr>
        <th>user_id</th>
        <th>Login</th>
        <th>Password</th>
        <th>type id</th>
        <th>date</th>
    </tr>
    <?php foreach ($users as $user):?>
    <tr>
        <td><?php echo $user->user_id ?></td>
        <td><?php echo $user->login ?></td>
        <td><?php echo $user->password ?></td>
        <td><?php echo $user->type_id ?></td>
        <td><?php echo $user->create_date ?></td>
    </tr>

    <?php endforeach;
    ?>
    </table>
<?php endif;







