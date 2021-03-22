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


    if($listImages!= null)
    {
      static $req2 = null;
      if($req2 == null)
      $req2 = db()->prepare($sqlAddImage);
    
      foreach($listImages as $img)
      {   
        $req2->bindParam(':NOM',$img[0],PDO::PARAM_STR);
        $req2->bindParam(':EXT',$img[1],PDO::PARAM_STR);
        $req2->bindParam(':IDPOST',$idPost,PDO::PARAM_INT);
        $req2->execute();
      }
    }
    db()->commit();
    return true;
    } catch (Exception $e) {
      db()->rollBack();
    return false;
    }
}

function updatePost($idPost,$commentaire,$UserPostMedia)
{
  try
  {
    db()->beginTransaction();
    static $ps = null;
    $sql = "UPDATE `post` SET ";
    $sql .= "`commentaire` = :COMMENTAIRE ";
    $sql .= "WHERE (`id` = :IDPOST)";
    if ($ps == null) 
    {
      $ps = db()->prepare($sql);
    }

   $ps->bindParam(':COMMENTAIRE',$commentaire,PDO::PARAM_STR);
   $ps->bindParam('IDPOST', $idPost,PDO::PARAM_INT);
   $ps->execute();

   if($UserPostMedia != null)
    { static $psMedia = null;
      $sqlMedia = "INSERT INTO `media` (`nomFichierMedia`,`typeMedia`,`idPost`) VALUES (:NOM,:EXT,:IDPOST)";
      if ($psMedia == null) 
      {
        $psMedia = db()->prepare($sqlMedia);
      }
      static $psMediaDelete = null;
      $sqlMediaDelete = "DELETE FROM `media` WHERE (`idPost` = :IDPOST)";
      if ($psMediaDelete == null) 
      {
        $psMediaDelete = db()->prepare($sqlMediaDelete);
      }
     $psMediaDelete->bindParam('IDPOST', $idPost,PDO::PARAM_INT);
     $psMediaDelete->execute();
      foreach($UserPostMedia as $media)
      {   
        $psMedia->bindParam(':NOM',$media[0],PDO::PARAM_STR);
        $psMedia->bindParam(':EXT',$media[1],PDO::PARAM_STR);
        $psMedia->bindParam('IDPOST',$idPost,PDO::PARAM_INT);
        $psMedia->execute();
      }
   }
   db()->commit();
   return true;
  }
  catch(PDOException $e)
  {
    db()->rollBack();
    return false;
  }
}

/**
 * Supprime la note ave l'id $idnote.
 * @param int $idnote 
 * @return bool 
 */
function deletePost($idPost)
{
    try 
    {
      static $ps = null;
      $sql = "DELETE FROM `post` WHERE (`id` = :ID);";
      if ($ps == null) 
      {
        $ps = db()->prepare($sql);
      }
      db()->beginTransaction();
      $ps->bindParam(':ID', $idPost, PDO::PARAM_INT);
      $ps->execute();

      static $psMediaDelete = null;
      $sqlMediaDelete = "DELETE FROM `media` WHERE `idPost` is NULL;";
      if ($psMediaDelete == null) 
      {
        $psMediaDelete = db()->prepare($sqlMediaDelete);
      }
      $psMediaDelete->execute();    
      
      db()->commit();
      return true;
    } 
    catch (PDOException $e) 
    {
      db()->rollBack();
      return false;
    }

}


?>