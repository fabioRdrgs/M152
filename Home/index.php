<?php
require_once '../php/user_show_post.inc.php';
$arrayPosts = show_all_Posts();
//Ajout d'un post null afin d'empêcher qu'un index inexistant soit testé lors de l'affichage des posts
array_push($arrayPosts,null);
$mediaPost = [];
$postCount;
if (!isset($_SESSION))
  session_start();
$_SESSION['currentPage'] = "Home";
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <script src="https://kit.fontawesome.com/b028745828.js" crossorigin="anonymous"></script>
  <title>The Zone - MonEspace</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/blog-home.css" rel="stylesheet">

</head>

<body>
  <!-- Template Post
        <div class="card mb-4">
          <img class="card-img-top" src="http://placehold.it/750x300" alt="Card image cap">
          <div class="card-body">
            <h2 class="card-title">Post Title</h2>
            <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reiciendis aliquid atque, nulla? Quos cum ex quis soluta, a laboriosam. Dicta expedita corporis animi vero voluptate voluptatibus possimus, veniam magni quis!</p>
            <a href="#" class="btn btn-primary">Read More &rarr;</a>
          </div>
          <div class="card-footer text-muted">
            Posted on January 1, 2020 by
            <a href="#">Start Bootstrap</a>
          </div>
       </div>
    -->
  <!--#region Navigation  -->

  <!-- Navigation -->
  <?php include_once "../php/navbar.php" ?>

  <!--#endregion -->

  <!-- Page Content -->

  <div class="container">
    <div class="row">
      <!-- Profile column -->
      <div class="col-md-4">
        <!-- Profile Info -->
        <div class="card my-4">
          <div class="card-header">
            <img src="../img/Ville.png" alt="Image du Blog" class="card-img-top">
            <h6 class="card-text">Bienvenue sur MonEspace</h6>
          </div>
          <div class="card-body">
            <h6 class="card-title">F. Santos</h6>
            <p class="card-text">239 Followers, <?php if (isset($arrayPosts)) echo count($arrayPosts)-1; ?> posts</p>
            <img src="../img/NoPFP.jpg" alt="Image de Profile" class=" card-img-bottom" style="width:20%">
          </div>
        </div>
      </div>


      <!-- Post Column -->
      <div class="col-md-8">

        <?php
        //For permettant d'afficher chaque posts
        for ($i = 1; $i < count($arrayPosts) ; $i++) 
        {
          if ($arrayPosts[$i - 1]['nomMedia'] != null) 
          {
            //Si l'id actuel est égal à l'id précédant, insère le nom de l'image ainsi que son extension dans un array pour utilisation future
            if ( $i == count($arrayPosts) || $arrayPosts[$i - 1]['idPost'] == $arrayPosts[$i]['idPost']) 
            {
              array_push($mediaPost, [$arrayPosts[$i - 1]['nomMedia'], $arrayPosts[$i - 1]['extMedia']]);
            }
            //Lorsque l'id n'est plus le même, cela veut dire qu'il y a un autre post actuellement. Donc nous allons ajouter la dernière image à l'array
            //Précédement crée et afficher le post précédant avant de vider l'array pour recommencer l'opération autant de fois que le for se lance qui est
            //égal aux d'images au total pour tous les posts
            else 
            {
              array_push($mediaPost, [$arrayPosts[$i - 1]['nomMedia'], $arrayPosts[$i - 1]['extMedia']]);

              //Affiche le post
              echo
              "<div class=\"card mb-4\"> ";
              //Affiche chaque images
              foreach ($mediaPost as $media) 
              {
                if ($media[1] == "mp4" || $media[1] == "ogg" || $media[1] == "webm")
                  echo "
                      <video autoplay controls loop>
                      <source  src=\"../tmp/" . $media[0] . "." . $media[1] . "\" type=\"video/mp4\">
                      <source  src=\"../tmp/" . $media[0] . "." . $media[1] . "\" type=\"video/ogg\">
                      <source  src=\"../tmp/" . $media[0] . "." . $media[1] . "\" type=\"video/webm\">
                      Your browser does not support the video tag.
                      </video>";
                else if ($media[1] == "mpeg" || $media[1] == "ogg") 
                {
                  echo "
                          <audio autostart=\"false\" controls>
                          <source  src=\"../tmp/" . $media[0] . "." . $media[1] . "\" type=\"audio/ogg\">
                          <source  src=\"../tmp/" . $media[0] . "." . $media[1] . "\" type=\"video/mpeg\">
                          </audio>";
                } else
                  echo "<img style=\"width:300px;height:250px;\"class=\"card-img-top\" src=\"../tmp/" . $media[0] . "." . $media[1] . "\" alt=\"Card image cap\">";
              }

              
              

              echo " <div class=\"card-body\"> 
                    <p class=\"card-text\">";
              //Affiche le commentaire
              echo $arrayPosts[$i - 1]['postCommentaire'];
              echo "</p>
                      <a href=\"../Post/index.php?idPost=" . $arrayPosts[$i - 1]['idPost'] . "\" class=\"btn btn-primary\">Modifier le post</a> 
                      <a href=\"../php/deleteFile.php?idPost=" . $arrayPosts[$i - 1]['idPost'] . "\" class=\"btn btn-primary\">Supprimer le post</a>
                      </div> 
                      <div class=\"card-footer text-muted\"> Posté le : ";
              //Affiche la date de création du post
              $dateCreationPost =  explode(" ", $arrayPosts[$i - 1]['postDateCreation']);
              echo $dateCreationPost[0] . " à " . $dateCreationPost[1];

              echo " by F. Santos</div></div>";

              $mediaPost = [];
            }
          } 
          else if ($arrayPosts[$i - 1]['nomMedia'] == null)
          {
            echo "<div class=\"card mb-4\"> ";
            echo " <div class=\"card-body\"> 
                    <p class=\"card-text\">";
            //Affiche le commentaire
            echo $arrayPosts[$i - 1]['postCommentaire'];
            echo "</p>
                    <a href=\"../Post/index.php?idPost=" . $arrayPosts[$i - 1]['idPost'] . "\" class=\"btn btn-primary\">Modifier le post</a> 
                    <a href=\"../php/deleteFile.php?idPost=" . $arrayPosts[$i - 1]['idPost'] . "\" class=\"btn btn-primary\">Supprimer le post</a>
                </div> 
                      <div class=\"card-footer text-muted\"> Posté le : ";
            //Affiche la date de création du post
            $dateCreationPost =  explode(" ", $arrayPosts[$i - 1]['postDateCreation']);
            echo $dateCreationPost[0] . " à " . $dateCreationPost[1];

            echo " by F. Santos</div></div>";
          }
        }


        ?>
      </div>
    </div>
    <!-- /.container -->
  </div>
  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; TheZone</p>
    </div>
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>