<?php
require_once '../../model/News.php';
require_once '../../model/Images.php';
require_once '../../header.php';
require_once '../../footer.php';

$displayForm = false;

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if (isset($_GET['news_id'])) {
    $id = $_GET['news_id'];
}

if (isset($action)) {
    if ($action == "create") {
        $displayForm = true;
        // si le formulaire a été submit
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            if ($_POST['image_id']=='--'){
                $image_id = NULL;

            }
            else{
                $image_id = $_POST['image_id'];


            }

            $link = $_POST['link'];

            $sql = "INSERT INTO news(title, description, image_id, link) VALUES (:title, :description, :image_id, :link);";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':image_id', $image_id, PDO::PARAM_INT);
                $statement->bindParam(':link', $link, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo "Erreur : " . $e->getMessage();
            }
            // on retire le formulaire
            $displayForm = false;

        }
    } else if ($action == "update") {
        // si le formulaire a été submit
        if (isset($_POST['title'])) {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $image_id = $_POST['image_id'];
            $link = $_POST['link'];
            $id = $_POST['news_id'];


            $sql = "UPDATE news SET title=:title, description=:description, image_id=:image_id, link=:link WHERE news_id=:id;";


            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':image_id', $image_id, PDO::PARAM_INT);
                $statement->bindParam(':link', $link, PDO::PARAM_STR);
                $statement->execute();
            } catch (PDOException $e) {
                echo 'Erreur : ' . $e->getMessage();
            }

        } else {
            $displayForm = true;
            $infoNews = getNews($id);
        }
    } else if ($action == "delete" && !empty($id)) {

        $sql = "DELETE FROM news WHERE news_id = :id";

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
$news = getAllNews();
$idImage = getIdImage();
echo "<pre>" . print_r($_POST, true) . "</pre>";


if ($displayForm) {

    echo '
    <form name="newsForm" method="POST" action="?action=' . $action . '" onsubmit= "return validateForm(\'newsForm\',\'title\'); " required>
        Title : <input type="text" name="title"  value="' . ($action == 'update' ? $infoNews->title : '') . '" />
        <br>
        Description : <input type="text" name="description"  value="' . ($action == 'update' ? $infoNews->description : '') . '">
        <br>';
    if (!empty($idImage)) {
        echo ' Image_id : 
            <select name="image_id">';
        echo '<option value="--">--</option>';

        foreach ($idImage as $idImg) {
            echo '<option value="' . ($action == 'update' ? $infoNews->image_id : '') . '">' . $idImg->image_id . '</option>';
        }
        echo '</select>
            <br>';

    }
    echo '
        Link : <input type="text" name="link"  value="' . ($action == 'update' ? $infoNews->link : '') . '">
        <br>
        <input type="hidden" name="news_id" value="' . ($action == 'update' ? $id : '' ) . '">
        <input type="submit" name="submit" value="submit">
    </form>
    ';
} else {
    echo '
    <table>
        <tr>
            <th>news_id</th>
            <th>title</th>
            <th>description</th>
            <th>image_id</th>
            <th>link</th>
            <th>action</th>
            <th>supprimer</th>
        </tr>
    ';

    foreach ($news as $info) {
        echo '<tr>';
        echo '<td>' . $info->news_id . '</td>';
        echo '<td>' . $info->title . '</td>';
        echo '<td>' . $info->description . '</td>';
        echo '<td>' . $info->image_id . '</td>';
        echo '<td>' . $info->link . '</td>';
        echo '<td> <a href="?action=update&news_id=' . $info->news_id . '">edit</a> </td>';
        echo '<td> <a href="?action=delete&news_id=' . $info->news_id . '">delete</a> </td>';
        echo '</tr>';
    }

    echo '</table>';
}

echo '<a href="?action=create">Create</a>';
echo '<br>';
echo '<a href="Users.php">UserCRUD</a>';
echo '<br>';
echo '<a href="Types.php">typeCrud</a>';
?>

</body>


<link href="../css/userCrud.css" rel="stylesheet" media="screen">




