<?php 

if(!isset($_SESSION))
  session_start();?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container"> 
      <a class="navbar-brand" href="../Home/"><i class="fas fa-tenge"></i> TheZone</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <form class="form-inline">
        <div class="input-group">                    
            <input type="text" class="form-control" placeholder="Rechercher">
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary"><i class="fa fa-search"></i></button>
            </div>
    </div>
    </form>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item <?php if($_SESSION['currentPage'] == "Home")echo "active";?>">
            <a class="nav-link" href="../Home/">Home</a>
          </li>
          <li class="nav-item  <?php if($_SESSION['currentPage'] == "Post")echo "active";?>">
            <a class="nav-link" href="../Post/">Post</a>
          </li>       
        </ul>
      </div>
    </div>
</nav>
 