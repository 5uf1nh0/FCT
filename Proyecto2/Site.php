<?php

class Site{
  private $idsite;
  private $webSite;
  private $image;
  private $status;

  void __construct(){
    this->$idsite = null;
    this->$webSite = '';
    this->$image = 'Dummy.png';
    this->$status = 1;
  }

  void construct($idsite,$webSite,$image,$status){
    this->$idsite = $idsite;
    this->$webSite = $webSite;
    this->$image = $image;
    this->$status = $status;
  }
  
  public void function removeSite($idsite){
    if((this->$idsite == $idsite) && (this->$status == 1)){
      this->$status = 0;
    }
  }

  //Getters
  public function getIdSite(){
    return $this->$idsite;
  }
  
  public function getWebSite(){
    return $this->$webSite;
  }
  
  public function getImage(){
    return $this->$image;
  }
  
  public function getStatus(){
    return $this->$status;
  }   
  
  //Setters
  public function setIdSite($idsite){
    $this->$idsite = $idsite;
  }
  
  public function setWebSite($webSite){
    $this->$webSite = $webSite;
  }
  
  public function setImage($image){
    $this->$image = $image;
  }

  public function setStatus($status){
    $this->$status = $status;
  }

}

?>