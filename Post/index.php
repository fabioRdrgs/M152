<?php
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
<textarea id="postTextArea" rows="3" cols="50"></textarea></br>
<label for="fileSelect"> Select a file:</label> <input id="fileSelect" accept="image/*" type="file" name="imgSelect[]" multiple>
<input type="submit">
</form>

<?php 
for($i = 0; $i < count($_FILES["imgSelect"]['name']) ; $i++)
{
  if(move_uploaded_file($_FILES["imgSelect"]["tmp_name"][$i],"../tmp/".$_FILES["imgSelect"]["name"][$i]))
  echo "Fichiers uploadés";
  else
  echo "Error lors de l'upload";

}
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
