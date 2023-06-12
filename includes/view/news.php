<?php
require_once '../header.php';
require_once '../model/news.php';
require_once '../model/images.php';

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
                $imageId = null;

            }
            else{
                $imageId = $_POST['image_id'];


            }

            $link = $_POST['link'];
            $PublicationDate = $_POST['PublicationDate'];
            $enable = $_POST['enable'];
            //echo "<pre>" . print_r($_POST, true) . "</pre>";


            $sql = "INSERT INTO news(title, description, image_id, link, PublicationDate, enable) VALUES (:title, :description, :image_id, :link, :date, :enable);";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':image_id', $imageId, PDO::PARAM_INT);
                $statement->bindParam(':link', $link, PDO::PARAM_STR);
                $statement->bindParam(':date', $PublicationDate, PDO::PARAM_STR);
                $statement->bindParam(':enable', $enable, PDO::PARAM_STR);
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
            if ($_POST['image_id']=='--'){
                $imageId = null;
            }
            else{
                $imageId = $_POST['image_id'];
            }
            $link = $_POST['link'];
            $id = $_POST['news_id'];
            $PublicationDate = $_POST['PublicationDate'];
            $enable = $_POST['enable'];


            $sql = "UPDATE news SET title=:title, description=:description, image_id=:image_id, link=:link, PublicationDate=:date, enable=:enable WHERE news_id=:id;";

            try {
                global $pdo;
                $statement = $pdo->prepare($sql);
                $statement->bindParam(':id', $id, PDO::PARAM_INT);
                $statement->bindParam(':title', $title, PDO::PARAM_STR);
                $statement->bindParam(':description', $description, PDO::PARAM_STR);
                $statement->bindParam(':image_id', $imageId, PDO::PARAM_INT);
                $statement->bindParam(':link', $link, PDO::PARAM_STR);
                $statement->bindParam(':date', $PublicationDate, PDO::PARAM_STR);
                $statement->bindParam(':enable', $enable, PDO::PARAM_STR);
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

echo '<div class="container">';

if ($displayForm) {


    echo '
    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-title">
                                    <h4>Add News</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-validation">
                                    
    <form class="form-valide" name="newsForm" method="POST" action="?action=' . $action . '"   >
        
        <div class="form-group row ">
        <label class="col-lg-3 col-form-label" for="title" >Title :<span class="text-danger"> *</span></label>  
        <div class="col-lg-9">
        <input class="form-control" type="text" name="title" placeholder="Title"  value="' . ($action == 'update' ? $newsInfo->title : '') . '" onkeypress="verifierCaracteres(event); return false;" />
        </div>
        </div>
        
        <br>
        
        <div class="form-group row ">
        <label class="col-lg-3 col-form-label">Description:</label>
        <div class="col-lg-9">
         <textarea id="tiny" name="description">' . ($action == 'update' ? $newsInfo->description : '') . '</textarea>
        </div>
        </div>
        
        
        <br>';


    if (!empty($images)) {
        echo '
                <div class="form-group row ">
                <label class="col-lg-3 col-form-label">ImageId:</label>
                <div class="col-lg-9">
                <select class="form-control" name="image_id" onchange="getImageSelect( this.value )" >
                
        <option value="--">--</option>';

        foreach ($images as $image) {
            $selected = ($image->image_id == $newsInfo->image_id) ? 'selected="selected"' : '';

            echo '<option value="' . $image->image_id . '" ' . $selected . '  >' . $image->name . '</option>';
        }
        echo '</select>
        </div>
        </div>
          
            <br>
       <div id="test" >
            
    </div>';


    }
    echo '
        
    <div class="form-group row ">                             
    <label class="col-lg-3 col-form-label">Link :</label>
        <div class="col-lg-9">
         <input class="form-control" type="text" name="link" placeholder="Link" value="' . ($action == 'update' ? $newsInfo->link : '') . '" onkeypress="verifierCaracteres(event); return false;" autocomplete="off" />
        </div>
        </div>
   
        <br>
        
        <div class="form-group row ">                             
        <label class="col-lg-3 col-form-label">Date de publication :</label>
        <div class="col-lg-9">
         <input class="form-control" type="date" id="date" name="PublicationDate" value="' . ($action == 'update' ? $newsInfo->PublicationDate : '') . '">
        </div>
        </div>
        
        
        <br>';

        if ($action == "update") {
            $selected = $newsInfo->enable;
        }
        echo '

        <div class="form-group row ">                                     
        <label class="col-lg-3 col-form-label">Enable :</label>
        
        <div class="col-lg-4">        
        <input type="radio"  name="enable" value="' . ($action == "update" && $selected == 1 ? 'checked' : '1') . '">
            <label>True</label>
            </div>';
        

        //echo $selected;

        echo '
         <div class="col-lg-5">        
        <input type="radio"  name="enable" value="' . ($action == "update" && $selected == 0 ? 'checked="checked"' : '0') . '">
            <label>False</label>
        </div>
        
        
        </div>
        <br>
        
        <input type="hidden" name="news_id" value="' . ($action == 'update' ? $id : '' ) . '">
        
        <br>
        
        <a class="btn btn-default btn-flat btn-addon m-b-10 m-l-5" href="news.php"><i class="ti-back-left"></i></span>Retour</a>
        <button type="submit" name="submit"  class="btn btn-success btn-flat btn-addon m-b-10 m-l-5"><i class="ti-check"></i>Submit</button>
                         
    </form>
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
            <th style="width: 150px;">#</th>
            <th style="width: 150px;">Title</th>
            <th style="width: 150px;">Description</th>
            <th style="width: 150px;">Image</th>
            <th style="width: 150px;">Link</th>
            <th style="width: 150px;">Publication Date</th>
            <th style="width: 150px;">Enable</th>
            <th style="width: 100px;">
                <a href="?action=create"><span class="jsgrid-button jsgrid-mode-button jsgrid-insert-mode-button ti-plus jsgrid-align-center" type="button" title=""></span></a>
            </th>  
        </tr>
 </thead>
    <tbody>';

    foreach ($newsCollection as $news) {
        echo '<tr class="jsgrid-align-center" data-newsid="' . $news->news_id . '" style="display: table-row;">';
        echo '<td style="width: 150px;">' . $news->news_id . '</td>';
        echo '<td style="width: 100px;">' . $news->title . '</td>';
        echo '<td style="width: 100px;">' . $news->description . '</td>';
        $imageById = isset($news->image_id) ? getImageById($news->image_id) : null;
        if ($imageById) {
            echo '<td style="width: 150px;">';
            echo '<img id="imageTabNews" src="' . getImage($news->image_id) . '">' . PHP_EOL;
            echo '</td>';
        } else {
            echo '<td style="width: 100px;">' . "- - -" . '</td>';
        }
        echo '<td style="width: 100px;">' . $news->link . '</td>';
        echo '<td style="width: 100px;">' . $news->PublicationDate . '</td>';
        if ($news->enable==1){
            echo '
            <td style="width: 100px;"><i class="ti-check color-success border-success"></i></td>
            ';
        } else {
            echo '
            <td style="width: 100px;"><i class="ti-close color-danger border-danger"></i></td>';

        }
        echo '<td style="width: 50px;"> 
                <a href="?action=update&news_id=' . $news->news_id . '"><span class="jsgrid-button jsgrid-edit-button ti-pencil" type="button" title="Edit"  ></span></a> 
                <a href="?action=delete&news_id=' . $news->news_id . '"><span class="jsgrid-button jsgrid-delete-button ti-trash" type="button" title="Delete"></span></a> 
                </td>';

        echo '</tr>';
    }
}';

    </tbody>
    </table>
    </div>
    </div>
                                </div>
                            </div>
                            <!-- /# card -->
                        </div>
                        <!-- /# column -->
                    </div>
                    <!-- /# row -->

                    
                </div>';

echo '</div>';

function getImage(string $id)
{
    $imageDirectory = '../assets/images/upload/';

    foreach (['jpg', 'jpeg', 'png'] as $extension) {
        if (file_exists($imageDirectory . $id . '.' . $extension)) {
            return $imageDirectory . $id . '.' . $extension;
        }
    }
    return $imageDirectory . "default.png";
}


require_once '../footer.php';

?>






