<?php
if(!isset($_SESSION))
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
<?php include_once "../php/navbar.php"?>
 
  <!--#endregion -->
 
  <!-- Page Content -->

  <div class="container">
  
      <!-- Sidebar Widgets Column -->
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
  <!-- /.container -->

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
