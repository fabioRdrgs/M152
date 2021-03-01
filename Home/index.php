<?php
require_once '../php/user_show_post.inc.php';
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
            <p class="card-text">239 Followers, 0 Posts</p>
            <img src="../img/NoPFP.jpg" alt="Image de Profile" class=" card-img-bottom" style="width:20%">
          </div>
        </div>
      </div> 
 
    
      <!-- Post Column -->
    <div class="col-md-8">

     <?php
     $arrayPosts = show_all_Posts();
     $imgsPost =[];
      //For permettant d'afficher chaque posts
     for($i = 1; $i <= count($arrayPosts);$i++)
     {
       //Si l'id actuel est égal à l'id précédant, insère le nom de l'image ainsi que son extension dans un array pour utilisation future
        if($arrayPosts[$i-1]['idPost'] == $arrayPosts[$i]['idPost'])
        {
          array_push($imgsPost,[$arrayPosts[$i-1]['NomImage'],$arrayPosts[$i-1]['extImage']]);                   
        }
        //Lorsque l'id n'est plus le même, cela veut dire qu'il y a un autre post actuellement. Donc nous allons ajouter la dernière image à l'array
        //Précédement crée et afficher le post précédant avant de vider l'array pour recommencer l'opération autant de fois que le for se lance qui est
        //égal aux d'images au total pour tous les posts
        else
        {
          array_push($imgsPost,[$arrayPosts[$i-1]['NomImage'],$arrayPosts[$i-1]['extImage']]); 

          //Affiche le post
          echo
           "<div class=\"card mb-4\"> ";
                //Affiche chaque images
                foreach($imgsPost as $img)
                {
                 echo "<img style=\"width:300px;height:100px;\"class=\"card-img-top\" src=\"../tmp/".$img[0].".".$img[1]."\" alt=\"Card image cap\">";
                }
               echo " <div class=\"card-body\"> 
                <p class=\"card-text\">";
                //Affiche le commentaire
                echo $arrayPosts[$i-1]['postCommentaire'];
                echo "</p>
                  <a href=\"#\" class=\"btn btn-primary\">Read More &rarr;</a> 
                  </div> 
                  <div class=\"card-footer text-muted\"> Posté le : ";
                  //Affiche la date de création du post
                  $dateCreationPost =  explode(" ",$arrayPosts[$i-1]['postDateCreation']);
                  echo $dateCreationPost[0]. " à ". $dateCreationPost[1];

                  echo " by F. Santos</div> 
            </div>"; 
            $imgsPost = [];         
        }
     }

     
     ?>
     

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
    </div>
     
    <!-- /.container -->

  </div>
  <!-- Footer -->
  <footer class="py-5 bg-dark">
      <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; TheZone</p>
      </div>
      <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>