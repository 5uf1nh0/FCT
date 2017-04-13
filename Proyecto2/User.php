<?php

INCLUDE ('DBHandler.php');
INCLUDE ('conexion.php');

class User extends DBHandler{
  private $iduser = null;
  private $name = null;
  private $pass = null;
  private $dob = null;
  private $sex = null;

  public function __construct($name,$pass){
    if($name !== null && $pass !== null){
      $this->name = $name;
      $this->pass = $pass;
      $this->load();
    }
  }
  
  //Other Private Methods
  private function load(){
    $mysqli = new mysqli(DBHOST , DBUSER , DBPASS, DB);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    $sql="SELECT users.id, users.`name`,users.`date`,users.sex
	  FROM users
	  WHERE users.`name` = ? and users.`password` = ?;";
    
    $nName = $this->getName();
    $pPass = $this->getPass();
    
    $db->add('s' , $nName);
    $db->add('s' , $pPass);
    
    $db->prepareStatement($sql);
    $res = $db->query();
    $db->clear();
    
    foreach ($res as $fila) {
      $this->iduser = $fila['id'];
      $this->name = $fila['name'];
      $this->dob = $fila['date'];
      $this->sex = $fila['sex'];
    }
    
  }
  
  private function save($iduser){
    if($iduser !== null){
      $sql="INSERT INTO  
	  VALUES  
	  WHERE ";
    }
  }
  
  private function delete($iduser){
    $sql="UPDATE 
	  SET  
	  WHERE ";
  }
  
  //Getters
  public function getId(){
    return $this->iduser;
  }
  
  public function getName(){
    return $this->name;
  }
  
  public function getPass(){
    return $this->pass;
  }
  
  public function getDateOfBirth(){
    return $this->dob;
  }
  
  public function getSex(){
    return $this->sex;
  }   
  
  //Setters
  public function setId($iduser){
    $this->$iduser = $iduser;
  }
  
  public function setName($name){
    $this->$name = $name;
  }
  
  public function setPass($pass){
    $this->$pass = $pass;
  }
    
  public function setDateOfBirth($dob){
    $this->$date = $dob;
  }

  public function setSex($sex){
    $this->$sex = $sex;
  }
  
}

?>