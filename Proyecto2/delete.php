<?php

ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('Site.php');
require_once('fxl_template.inc.php');

$id = $_SESSION['user_id'];

if(isset($_POST['checked'])){
  $check = $_POST['checked'];
  
  $nSite = new Site($id,null,null);  
    
  foreach($check as $idsite) {
    $status = 0;
    $nSite->hideSites($idsite);     
  }
    
    $status=1;
    $nSite->showSites($id);
    
    $fxlt_cont = new  fxl_template('profile_cont.tpl');
    $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
    $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
    
    $resultSet = $nSite->getResult();
    
    foreach ($resultSet as $fila) {
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