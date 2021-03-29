<?php
require_once "../php/sql_func.inc.php";
require_once "../php/user_show_post.inc.php";
$commentairePost = filter_input(INPUT_POST, 'postTextArea', FILTER_SANITIZE_STRING);

if (!isset($_SESSION))
  session_start();

$_SESSION['currentPage'] =  "Post";

//Si l'on est sur la page pour modifier un post, récupère les informations du post en question
if (isset($_GET['idPost'])) {
  $infoPost = show_post_by_id($_GET['idPost']);
}
//echo $_GET['idPost'];

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
  <?php include_once "../php/navbar.php"; var_dump($commentairePost);?>
  <!--#endregion -->
  <?php
  //var_dump($infoPost);
  //Teste si on est là pour modifier un post ou en créer un
  if (isset($_GET['idPost'])) {
    //Affiche les champs permettant de modifier le post choisi
    echo "<form id=\"updateForm\" action=\"./index.php?idPost=" . $_GET['idPost'] . "\" method=\"POST\" enctype=\"multipart/form-data\">
    <label for=\"postTextArea\">Entrez du text</label></br>
    <textarea required name=\"postTextArea\" id=\"postTextArea\" rows=\"3\" cols=\"50\">" . $infoPost[0]['postCommentaire'] . "</textarea></br>
    <label for=\"fileSelect\"> Select a file:</label> <input id=\"fileSelect\" accept=\".png, .bmp, .jpg, .jpeg, .gif, .mp4, .mp3, .ogg\" type=\"file\" name=\"mediaSelect[]\" multiple>
    <input id=\"update\" name=\"submit\" type=\"submit\">
  </form>";
   
    if ($infoPost[0]['nomMedia'] != null)
      for ($i = 0; $i < count($infoPost); $i++) {
        echo "Images du post : ";
        echo "<img style=\"width:300px;height:250px;\"class=\"card-img-top\" src=\"../tmp/" . $infoPost[$i]['nomMedia'] . "." . $infoPost[$i]['extMedia'] . "\" alt=\"Card image cap\">";
      }
  }
  //Affiche les champs de création d'un nouveau post 
  else 
  {
    echo "<form action=\"./index.php\" method=\"POST\" enctype=\"multipart/form-data\">
    <label for=\"postTextArea\">Entrez du text</label></br>
    <textarea required name=\"postTextArea\" id=\"postTextArea\" rows=\"3\" cols=\"50\"></textarea></br>
    <label for=\"fileSelect\"> Select a file:</label> <input id=\"fileSelect\" accept=\".png, .bmp, .jpg, .jpeg, .gif, .mp4, .mp3, .ogg\" type=\"file\" name=\"mediaSelect[]\" multiple>
    <input name=\"submit\" type=\"submit\">
  </form>";
  }
  ?>

  <?php
  $UserPostMedia = [];
  $totalSize = 0;
  $totalCountMedia = 0;
  //var_dump($_POST);
  //var_dump($_FILES['mediaSelect']);

  //Teste si l'on a appuie sur un bouton submit et qu'on est en train de modifier un post
  if (isset($_POST['submit']) && isset($_GET['idPost']) && $_GET['idPost'] > -1) 
  {
    //Vérifie si le post modifié à reçu un à plusieurs nouveaux médias, le cas échéant définissant l'array de médias à null
    if ($_FILES['mediaSelect']['error'][0] != 0)
      $UserPostMedia = null;

      //Sinon, entre dans la section modifiant les médias du post en question
    if ($_FILES["mediaSelect"]['error'][0] == 0) {
      //var_dump($_FILES["mediaSelect"]);
      //Permet de vérifier le nombre de médias fournis
      for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) 
      {
        $totalSize += $_FILES["mediaSelect"]['size'][$i];
        $totalCountMedia++;
      }
      //echo $totalCountMedia;
      //Vérifie que l'on a bien fournit maximum 4 médias
      if ($totalCountMedia <= 4) 
      {
        //Vérifie que la taille totale des médias fournis ne dépasse pas 140 mo
        if ($totalSize < 140000000) 
        {
          //Va traiter tous les médias à l'aide d'un for qui va les parcourir
          for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) 
          {
            $Orgfilename = $_FILES["mediaSelect"]["name"][$i];
            $filename = uniqid();
            $dir = "../tmp/";
            // $listImages = array();
            $ext = explode("/", $_FILES["mediaSelect"]["type"][$i])[1];
            $file = $filename . '.' . $ext;

            //S'assure qu'un commentaire est bien fournit pour le post
            if ($commentairePost != "") 
            {
              //Teste que le format du média fournit est bien valide
              if (in_array($ext, ["png", "bmp", "jpg", "jpeg", "gif", "mp4", "ogg", "mpeg"]) && $_FILES["mediaSelect"]['size'][$i] < 15145728)
               {
                 //Ajoute le média actuel à l'array de médias du post en question
                array_push($UserPostMedia, [$filename, $ext]);
              } 
              else 
              {
                echo "Veuillez sélectionner des fichiers valides!";
                return;
              }
            } 
            else 
            {
              echo "Veuillez écrire un commentaire";
              return;
            }
          }
        } 
        else 
        {
          echo "Le total de fichiers fournis est trop lourd! Veuillez en sélectionner de plus légers";
          return;
        }
      } 
      else
        echo "Veuillez ne sélectionner que 4 médias maximum!";
      }
      //var_dump($UserPostMedia);

      //S'assure qu'un commentaire est bien fournit
    if($commentairePost != "")
    {
      //S'assure que le post ait bien été créé dans la BDD
      if (updatePost($_GET['idPost'], $commentairePost, $UserPostMedia)) 
      {
        //Si des nouveaux médias ont été fournis, va supprimer les médias actuels et les remplacer par les nouveaux
        if ($UserPostMedia != null)
        {
          //Parcours la liste des anciens médias et les supprime du serveur
          foreach($infoPost as $media)
          {
            unlink("../tmp/".$media['nomMedia'].".".$media['extMedia']);
          }
        
         //Upload les nouveaux médias
          for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) {
            if (move_uploaded_file($_FILES["mediaSelect"]["tmp_name"][$i], $dir . $UserPostMedia[$i][0] . "." . $UserPostMedia[$i][1])) {
              echo "Fichiers uploadés";
            }
          }
        }
        else
        {
         echo "Post créé";
         
        }
        header('location: ../Home/index.php');
      } 
      else
      {
        echo "Erreur lors de la création du Post";
      }
        header('location: ../Home/index.php');
      //var_dump($_FILES["mediaSelect"]);   
    }
    else
    echo "Veuillez écrirer un commentaire";
  } 
  else if (isset($_POST['submit'])) 
  {
    //Teste si des médias ont été fournis
    if (isset($_FILES['mediaSelect']) && $_FILES['mediaSelect']['error'][0] == 0) 
    {
     // var_dump($_FILES["mediaSelect"]);
      for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) {
        $totalSize += $_FILES["mediaSelect"]['size'][$i];
        $totalCountMedia++;
      }
//      echo $totalCountMedia;
//Teste si le nombre de médias n'excède pas 4
      if ($totalCountMedia <= 4) 
      {
        //Teste si le poids total n'excède pas 140MO
        if ($totalSize < 140000000) 
        {
          //Parcours la liste des médias afin de les traiter
          for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) 
          {
            $Orgfilename = $_FILES["mediaSelect"]["name"][$i];
            $filename = uniqid();
            $dir = "../tmp/";
            // $listImages = array();
            $ext = explode("/", $_FILES["mediaSelect"]["type"][$i])[1];
            $file = $filename . '.' . $ext;

            //S'assure qu'un commentaire est bien fournit
            if ($commentairePost != "") 
            {
              //Teste si format du média est valide
              if (in_array($ext, ["png", "bmp", "jpg", "jpeg", "gif", "mp4", "ogg", "mpeg"]) && $_FILES["mediaSelect"]['size'][$i] < 15145728) {

                array_push($UserPostMedia, [$filename, $ext]);
              } 
              else 
              {
                echo "Veuillez sélectionner des fichiers valides!";
                return;
              }
            } 
            else 
            {
              echo "Veuillez écrire un commentaire";
              return;
            }
          }
        } 
        else
        {
          echo "Le total de fichiers fournis est trop lourd! Veuillez en sélectionner de plus légers";
          return;
        }

        //S'assure que le post est bien créé sur la BDD 
        if (createNewPost($commentairePost, $UserPostMedia)) 
        {
          //Ajoute les médias fournis pour la création du post au serveur
          for ($i = 0; $i < count($_FILES["mediaSelect"]['name']); $i++) 
          {
            //S'assure que les médias ont bien été ajoutés
            if (move_uploaded_file($_FILES["mediaSelect"]["tmp_name"][$i], $dir . $UserPostMedia[$i][0] . "." . $UserPostMedia[$i][1])) {
              echo "Fichiers uploadés";
            }
          }
        } 
        else
          echo "Erreur lors de la création du Post";

        //var_dump($_FILES["mediaSelect"]);
      } else
        echo "Veuillez ne sélectionner que 4 médias maximum!";
    } 
    else 
    {
      //Crée le poste sur la BDD
      if (createNewPost($commentairePost, null)) {
        echo "Post Crée sans média!";
        header('location: ../Home/index.php');
      } 
      else 
      {
        echo "Erreur lors de la création du Post";
      }
    }
    header('location: ../Home/index.php');
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
  <script src="../js/update-post.js"></script>
</body>
</html>