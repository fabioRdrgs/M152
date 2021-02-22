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

function show_all_Posts()
{

}