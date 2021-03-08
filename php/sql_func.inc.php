<?php
/*
  Date       : ???
  Auteur     : F. Rodrigues dos Santos
  Sujet      : ???
 */
require "../php/constantes.inc.php";

/**
 * Connecteur de la base de données du .
 * Le script meurt (die) si la connexion n'est pas possible.
 * @staticvar PDO $dbc
 * @return \PDO
 */
function db()
{
  static $dbc = null;

  // Première visite de la fonction
  if ($dbc == null) {
    // Essaie le code ci-dessous
    try {
      $dbc = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPWD, array(
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_PERSISTENT => true
      ));
    }
    // Si une exception est arrivée
    catch (Exception $e) {
      echo 'Erreur : ' . $e->getMessage() . '<br />';
      echo 'N° : ' . $e->getCode();
      // Quitte le script et meurt
      die('Could not connect to MySQL');
    }
  }
  // Pas d'erreur, retourne un connecteur
  return $dbc;
}

function readById($id)
{
  static $ps = null;
  $sql = 'SELECT id, content FROM `table` WHERE id = :ID';

  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {
    $ps->bindParam(':ID', $id, PDO::PARAM_INT);

    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;
}
function readAll($limit = 0, $offset = 50)
{
  static $ps = null;
  $sql = 'SELECT id, content FROM `table` ORDER BY id ASC LIMIT :LIMIT,:OFFSET;';

  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {
    $ps->bindParam(':LIMIT', $limit, PDO::PARAM_INT);
    $ps->bindParam(':OFFSET', $offset, PDO::PARAM_INT);

    if ($ps->execute())
      $answer = $ps->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  return $answer;
}
function uploadImg($nom, $type)
{
  static $ps = null;
  $sql = "INSERT INTO `media` (`nomFichierMedia`,`typeMedia`)";
  $sql .= "VALUES (:NOM, :TYPE)";
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {
    $ps->bindParam(':NOM', $nom, PDO::PARAM_STR);
    $ps->bindParam(':TYPE', $type, PDO::PARAM_STR);

    $answer = $ps->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  return $answer;
}

function createNewPost($commentaire,$listImages)
{

$sqlCreateCom = "INSERT INTO `post` (`commentaire`) VALUES (:COMMENTAIRE)";
$sqlAddImage = "INSERT INTO `media` (`nomFichierMedia`,`typeMedia`,`idPost`) VALUES (:NOM,:EXT,:IDPOST)";


  try {
    static $req = null;
    if($req == null)
    $req = db()->prepare($sqlCreateCom );

    db()->beginTransaction();
    $req->bindParam(':COMMENTAIRE',$commentaire,PDO::PARAM_STR);
    $req->execute();
    $idPost = db()->lastInsertId();


    static $req2 = null;
    if($req2 == null)
    $req2 = db()->prepare($sqlAddImage);
  
    foreach($listImages as $img)
    {   
    $req2->bindParam(':NOM',$img[0],PDO::PARAM_STR);
    $req2->bindParam(':EXT',$img[1],PDO::PARAM_STR);
    $req2->bindParam('IDPOST',$idPost,PDO::PARAM_INT);
    $req2->execute();
    }
    db()->commit();
    return true;
    } catch (Exception $e) {
      db()->rollBack();
    return false;
    }
}

function update($content1, $content2, $content3, $content4, $content5)
{
  static $ps = null;

  $sql = "UPDATE `table` SET ";
  $sql .= "`content1` = :CONTENT1, ";
  $sql .= "`content2` = :CONTENT2, ";
  $sql .= "`content3` = :CONTENT3, ";
  $sql .= "`content4` = :CONTENT4 ";
  $sql .= "WHERE (`content5` = :CONTENT5)";
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {
     $ps->bindParam(':CONTENT1', $content1, PDO::PARAM_STR);
    $ps->bindParam(':CONTENT2', $content2, PDO::PARAM_STR);
    $ps->bindParam(':CONTENT3', $content3, PDO::PARAM_STR);
    $ps->bindParam(':CONTENT4', $content4, PDO::PARAM_STR);
    $ps->bindParam(':CONTENT5', $content5, PDO::PARAM_STR);
    $ps->execute();
    $answer = ($ps->rowCount() > 0);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  return $answer;
}

/**
 * Supprime la note ave l'id $idnote.
 * @param mixed $idnote 
 * @return bool 
 */
function deletePost($idPost)
{
  static $ps = null;
  $sql = "DELETE FROM `post` WHERE (`id` = :ID);";
  if ($ps == null) {
    $ps = db()->prepare($sql);
  }
  $answer = false;
  try {
    $ps->bindParam(':ID', $idPost, PDO::PARAM_INT);
    $ps->execute();
    $answer = true;
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
  return $answer;
}


?>