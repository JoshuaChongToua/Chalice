<?php
echo '<div class="container">';
require_once '../model/news.php';
require_once '../model/images.php';
require_once '../header.php';
require_once '../footer.php';

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
                $imageId = NULL;

            }
            else{
                $imageId = $_POST['image_id'];


            }

            $link = $_POST['link'];
            //echo "<pre>" . print_r($_POST, true) . "</pre>";


            $sql = "INSERT INTO news(title, description, image_id, link) VALUES (:title, :description, :image_id, :link);";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':image_id', $imageId, PDO::PARAM_INT);
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
            $newsInfo = getNews($id);
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
$newsCollection = getAllNews();
$images = getImages();

//echo "<pre>" . print_r($_POST, true) . "</pre>";


if ($displayForm) {


    echo '
    <form name="newsForm" method="POST" action="?action=' . $action . '" onsubmit= "return validateForm(\'newsForm\',\'title\'); "  >
        <label for="title" class="title-label">Title :</label>  
        <input type="text" name="title" placeholder="Title"  value="' . ($action == 'update' ? $newsInfo->title : '') . '" onkeypress="verifierCaracteres(event); return false;" />
        <br>
        Description : <input type="text" name="description" placeholder="Description" autocomplete="off"  value="' . ($action == 'update' ? $newsInfo->description : '') . '">
        <br>';
    if (!empty($images)) {
        echo ' Image_id : 
            <select name="image_id" onchange="getImageSelect( this.value )" >';
        echo '<option value="--">--</option>';

        foreach ($images as $image) {

            echo '<option value="' . $image->image_id . '" data-name="' . $image->name . '" data-id="$image->image_id"  >' . $image->name . '</option>';
        }
        echo '</select>
            <br>';
    }
    echo '
        Link : <input type="text" name="link" placeholder="Link" value="' . ($action == 'update' ? $newsInfo->link : '') . '" onkeypress="verifierCaracteres(event); return false;" autocomplete="off" />
        <br>
        <input type="hidden" name="news_id" value="' . ($action == 'update' ? $id : '' ) . '">
        <input type="submit" name="submit" value="submit">
    </form>
    ';
    echo '<div id="test" >
            
    </div>';

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
    //echo "<pre>" . print_r($newsCollection, true) . "</pre>";
    //echo "<pre>" . print_r($images, true) . "</pre>";

    foreach ($newsCollection as $news) {
        echo '<tr>';
        echo '<td>' . $news->news_id . '</td>';
        echo '<td>' . $news->title . '</td>';
        echo '<td>' . $news->description . '</td>';
        //$imageInfo = getImageById($news->image_id);
        //echo "<pre>" . print_r($imageInfo, true) . "</pre>";


        //echo '<td>' getImage(->)
        //echo "<pre>" . print_r($newsCollection, true) . "</pre>";
        $imageById = getImageById($news->image_id);
        echo '<td>' . $imageById->name . '</td>';
        echo '<td>' . $news->link . '</td>';
        echo '<td> <a href="?action=update&news_id=' . $news->news_id . '">edit</a> </td>';
        echo '<td> <a href="?action=delete&news_id=' . $news->news_id . '">delete</a> </td>';
        echo '</tr>';
    }

    echo '</table>';
}

echo '<a class="create" href="?action=create">Create</a>';
echo '</div>'
?>


<link href="../assets/css/news.css" rel="stylesheet" media="screen">




