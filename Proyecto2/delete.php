<?php

ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
INCLUDE ('DBHandler.php');
//INCLUDE ('Sites.php');
require_once('fxl_template.inc.php');


$id = $_SESSION['user_id'];

if(isset($_POST['checked'])){
  
  $check = $_POST['checked'];
  
  $mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
  $db = new DBHandler($mysqli);
  
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    
    foreach($check as $val) {
      $sqlup = "UPDATE sites SET estado = ? WHERE sites.id = ?";
      $est = 0;
      $db->add('i' , $est);
      $db->add('i' , $val);
      $db->prepareStatement($sqlup);
      $db->exec();
      $db->clear(); 
    }
    
    $sqlw = "SELECT sites.id,sites.website,sites.icon 
	    FROM sites 
	    WHERE sites.iduser = ? and sites.estado = ?;";
    $est=1;
    $db->add('i',$id);
    $db->add('i',$est);
    $db->prepareStatement($sqlw);
    $resw = $db->query();
    $db->clear();
    
    $fxlt_cont = new  fxl_template('profile_cont.tpl');
    $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
    $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
    
    foreach ($resw as $fila) {
      if($fila['icon']==''){
	$icon="images/dummy.jpeg";
      }else{
	$icon="images/".$fila['icon'];
      }
      
    $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $fila['id'],'sites' => $fila['website'], 'icons' => $icon)); 
  }

$fxlt_cont_site -> display();
    
}

?>