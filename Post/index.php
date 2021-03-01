<?php
require_once "../php/sql_func.inc.php";

$commentairePost = filter_input(INPUT_POST,'postTextArea',FILTER_SANITIZE_STRING);

if(!isset($_SESSION))
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
  <?php include_once "../php/navbar.php"?>
  <!--#endregion -->
<form action="./index.php" method="POST" enctype="multipart/form-data"> 

<label for="postTextArea">Entrez du text</label></br>
<textarea required name="postTextArea" id="postTextArea" rows="3" cols="50"></textarea></br>
<label for="fileSelect"> Select a file:</label> <input id="fileSelect" accept="image/*" type="file" name="imgSelect[]" multiple>
<input type="submit">
</form>

<?php 
$UserPostImages = [];
for($i = 0; $i < count($_FILES["imgSelect"]['name']) ; $i++)
{
  $Orgfilename = $_FILES["imgSelect"]["name"][$i];
  $filename = uniqid();
  $dir = "../tmp/";
  $listImages = array();
  $ext = explode("/",$_FILES["imgSelect"]["type"][$i])[1];
  $file = $filename.'.'.$ext;
  

  if(in_array($ext,["png","bmp","jpg","jpeg","gif"]) && $_FILES["imgSelect"]['size'][$i] < 3145728)
  {
    if($commentairePost != "")
    {
      array_push($UserPostImages, [$filename,$ext]);
    

     
    }
    else
    {
      echo "Veuillez écrire un commentaire";
      return;
    }
  
    
  }
  else
  {
    echo "Veuillez sélectionner des fichiers valides!";
    return;
  }


  

/*
    if(in_array($ext,["png","bmp","jpg","jpeg","gif"]) && $_FILES["imgSelect"]['size'][$i] < 3145728)
    {
      if($commentairePost != "")
      {
        if(move_uploaded_file($_FILES["imgSelect"]["tmp_name"][$i],$dir.$file))
        {       
          if(uploadImg($filename,$ext))
          echo "Fichiers uploadés";
          else
          {
            echo "Error lors de l'upload";
            unlink($dir.$file);
          }   
        }
        else
        echo "Error lors de l'upload";
      }
      else
      echo "Veuillez écrire un commentaire";
      
    }
    else
    echo "Veuillez sélectionner des fichiers valides!";*/
}
if(createNewPost($commentairePost,$UserPostImages))
  echo "Post Crée";
  else
  echo "Erreur lors de la création du Post";
var_dump($ext);
var_dump($_FILES["imgSelect"]);
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