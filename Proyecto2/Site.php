<?php

INCLUDE ('DBHandler.php');
INCLUDE ('conexion.php');

class Site extends DBHandler{
  private $idsite = null;
  private $webSite = null;
  private $icon = null;
  private $status = null;
  private $result;

  public function __construct($idUser, $webSite = null, $icon = null){
    if($webSite !== null && $icon !== null){
      $this->webSite = $webSite;
      $this->icon = $icon;
      $this->saveSite($idUser);
    }
    else{
      $this->loadProfile($idUser);
    }
  }
  
  private function saveSite($idUser){
    $mysqli = new mysqli(DBHOST , DBUSER , DBPASS, DB);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    $sql = "INSERT INTO sites(iduser,website,icon,estado) VALUES (?, ?, ?,?);";
    
    $sSite = $this->getWebSite();
    $iImage = $this->getImage();
    $status = 1;
    $db->add('i',$idUser);
    $db->add('s',$sSite);
    $db->add('s',$iImage);
    $db->add('i',$status);
    
    $db->prepareStatement($sql);
    $db->exec();
    $db->clear();
  }

  private function loadProfile($idUser){
    $mysqli = new mysqli(DBHOST , DBUSER , DBPASS, DB);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    $sql = "SELECT users.id,users.`name`,users.`date`,genero.sexo
	    FROM users,genero
	    WHERE users.id= ? and users.sex = genero.id;";

    $db->add('i',$idUser);
    $db->prepareStatement($sql);
    $result = $db->query();
    $db->clear();
    
    $this->result = $result;
    
  }
  
  private function showVisibles($idUser){
    $mysqli = new mysqli(DBHOST , DBUSER , DBPASS, DB);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    $sql = "SELECT sites.id,sites.website,sites.icon
	    FROM sites
	    WHERE sites.iduser = ? and sites.estado = ?;";
    
    $status=1;
    
    $db->add('i',$idUser);
    $db->add('i',$status);
    $db->prepareStatement($sql);
    
    $result = $db->query();
    $db->clear();
    
    $this->result = $result;
    
  }
  
  private function hideVisibles($idsite){
    $mysqli = new mysqli(DBHOST , DBUSER , DBPASS, DB);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    $sqlup = "UPDATE sites SET estado = ? WHERE sites.id = ?";
    
    $status = 0;
    $db->add('i',$status);
    $db->add('i',$idsite);
    $db->prepareStatement($sqlup);
    
    $db->exec();
    $db->clear();
  }
  
  //Public Methods
  public function showProfile($id){
    $this->loadProfile($id);
  }
  
  public function showSites($user_id){
    $this->showVisibles($user_id);
  }
  
  public function hideSites($user_id){
    $this->hideVisibles($user_id);
  }
  
  //Getters
  public function getIdSite(){
    return $this->idsite;
  }
  
  public function getWebSite(){
    return $this->webSite;
  }
  
  public function getImage(){
    return $this->icon;
  }
  
  public function getStatus(){
    return $this->status;
  }
  
  public function getResult(){
    return $this->result;
  }
  
  
  //Setters
  public function setIdSite($idsite){
    $this->idsite = $idsite;
  }
  
  public function setWebSite($webSite){
    $this->webSite = $webSite;
  }
  
  public function setImage($image){
    $this->image = $image;
  }

  public function setStatus($status){
    $this->status = $status;
  }

}

?>