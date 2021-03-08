<?php
require_once "../php/sql_func.inc.php";

$commentairePost = filter_input(INPUT_POST, 'postTextArea', FILTER_SANITIZE_STRING);

if (!isset($_SESSION))
  session_start();

$_SESSION['currentPage'] =  "Post";
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
  <link href="css/blog-post.css" rel="stylesheet">

</head>

<body>

  <!--#region Navigation  -->
  <!-- Navigation -->
  <?php include_once "../php/navbar.php" ?>
  <!--#endregion -->
  <form action="./index.php" method="POST" enctype="multipart/form-data">

    <label for="postTextArea">Entrez du text</label></br>
    <textarea required name="postTextArea" id="postTextArea" rows="3" cols="50"></textarea></br>
    <label for="fileSelect"> Select a file:</label> <input id="fileSelect" accept=".png, .bmp, .jpg, .jpeg, .gif, .mp4" type="file" name="mediaSelect[]" multiple>
    <input type="submit">
  </form>

  <?php
  $UserPostMedia = [];
  $totalSize = 0;
  $totalCountMedia = 0;
  if (isset($_FILES["mediaSelect"])) 
  {
    var_dump($_FILES["mediaSelect"]);
    for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) 
    {
      $totalSize += $_FILES["mediaSelect"]['size'][$i];
      $totalCountMedia++;
    }
     echo $totalCountMedia;
    if ($totalCountMedia <= 4)
     {

      if ($totalSize < 140000000)
       {
        for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) {
          $Orgfilename = $_FILES["mediaSelect"]["name"][$i];
          $filename = uniqid();
          $dir = "../tmp/";
          $listImages = array();
          $ext = explode("/", $_FILES["mediaSelect"]["type"][$i])[1];
          $file = $filename . '.' . $ext;

          if ($commentairePost != "") {
            if (in_array($ext, ["png", "bmp", "jpg", "jpeg", "gif", "mp4"]) && $_FILES["mediaSelect"]['size'][$i] < 15145728) {

              array_push($UserPostMedia, [$filename, $ext]);
            } else {
              echo "Veuillez sélectionner des fichiers valides!";
              return;
            }
          } else {
            echo "Veuillez écrire un commentaire";
            return;
          }
        }
      } else {
        echo "Le total de fichiers fournis est trop lourd! Veuillez en sélectionner de plus légers";
        return;
      }
      var_dump($UserPostMedia);

      if (createNewPost($commentairePost, $UserPostMedia)) {
        for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) {
          if (move_uploaded_file($_FILES["mediaSelect"]["tmp_name"][$i], $dir . $UserPostMedia[$i][0] . "." . $UserPostMedia[$i][1])) {
            echo "Fichiers uploadés";
          }
        }
      } else
        echo "Erreur lors de la création du Post";

      var_dump($_FILES["mediaSelect"]);
    }
    else
    echo "Veuillez ne sélectionner que 4 médias maximum!";
  }


  ?>

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