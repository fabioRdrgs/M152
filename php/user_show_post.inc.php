<?php 
require_once "../php/sql_func.inc.php";

function show_all_images()
{
 
  static $ps = null;
    $sql = 'SELECT nomFichierMedia as `NomImage`, typeMedia as `extImage` FROM `media` ORDER BY id ;';

  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {

    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;
}

function get_all_posts_id()
{
  $sql = "SELECT id FROM `post` ";
  static $ps = null;
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }

  $answer = false;
  try {
    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;
}

function show_all_Posts()
{
  $sql = "SELECT post.id as `idPost`,post.commentaire as `postCommentaire`, post.datePost as `postDateCreation`, nomFichierMedia as `NomImage`, typeMedia as `extImage` FROM `media` JOIN `post` ON (media.idPost = post.id) ORDER BY post.id";
  static $ps = null;
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }

  $answer = false;
  try {
    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;

/*
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {

    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;*/
}