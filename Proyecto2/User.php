<?php

class User{
  private $iduser;
  private $name;
  private $pass;
  private $date;
  private $sex;

  public __construct(){
    this->$iduser = null;
    this->$name = 'user';
    this->$pass = 'pass';
    this->$date = '00-00-0000';
    this->$sex = 0;
  }
  
  public __construct($iduser){
    if($iduser !== null){
      this->$iduser = $iduser;
      this->load();
    }
  }
  
  public construct($iduser,$name,$pass,$date,$sex){
    this->$iduser = $iduser;
    this->$name = $name;
    this->$pass = $pass;
    this->$date = $date;
    this->$sex = $sex;
  }
  
  //Getters
  public function getId(){
    return $this->$iduser;
  }
  
  public function getName(){
    return $this->$name;
  }
  
  public function getPass(){
    return $this->$pass;
  }
  
  public function getDateOfBirth(){
    return $this->$date;
  }
  
  public function getSex(){
    return $this->$sex;
  }   
  
  //Setters
  public function setName($iduser){
    $this->$iduser = $iduser;
  }
  
  public function setName($name){
    $this->$name = $name;
  }
  
  public function setPass($pass){
    $this->$pass = $pass;
  }
    
  public function setDateOfBirth($date){
    $this->$date = $date;
  }

  public function setDateOfBirth($sex){
    $this->$sex = $sex;
  }
  
  //Other Private Methods
  private function load(){
    $sql="SELECT 
	  FROM 
	  WHERE ";
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
  
}

?>