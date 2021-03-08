<?php
require './user_show_post.inc.php';
if (!isset($_SESSION))
session_start();

if(!isset($_GET['idPost']))
{
    header('location: ../Home/index.php');
    die("You are not authorized for this page!");
}
else
{
    $arrayMedia = getPostMedia($_GET['idPost']);
    var_dump($arrayMedia);
   
    if(deletePost($_GET['idPost']))
    {     
        foreach($arrayMedia as $media)
        {
            unlink("../tmp/".$media['nomMedia'].".".$media['extMedia']);
        }
    }
    header('location: ../Home/index.php');
    die("You are not authorized for this page!");
}