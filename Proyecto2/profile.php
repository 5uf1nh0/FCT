<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('Site.php');
require_once('fxl_template.inc.php');


if (isset($_REQUEST['site'])){
  if ( $_REQUEST['site'] != ''){
    
    $id= $_SESSION['user_id'];
    $site= $_POST['site'];
    $icon = $_FILES['file']['name'];
    
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Comprueba si el file es una imagen a traves de su tamanio
    if(isset($_POST["submit"])) {
      if($site != ''){
	  $uploadOk = 1;
      } else {
	  echo "Inserta pagina!";
	  $uploadOk = 0;
      }
    }

    // Permite ciertos formatos
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "pdf") { 
      echo "Sorry, only JPG, JPEG, PNG, GIF & PDF files are allowed.";
      $uploadOk = 0;
    }

    //Comprueba si no ha habido errores
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";

    // Si esta todo bien, sube el file
    } else {
    //Inserta las variables en la base solo si ha podido mover el file y además recibo las 2 variables del POST
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)){
	$nSite = new Site($id,$site,$icon);
      }else{
	  //Inserta en la DB $site y una imagen predefinida si $icon viene vacia y se ha podido mover el file
	  $nSite = new Site($id,$site);
      }
    }

$nSite->showSites($id);
    
$fxlt_cont = new  fxl_template('profile_cont.tpl');
$fxlt_cont_pro = $fxlt_cont -> get_block('profile');
$fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');

$reSet = $nSite->getResult();

foreach ($reSet as $fila) {
  if($fila['icon']==''){
    $icon="images/dummy.jpeg";
  }else{
    $icon="images/".$fila['icon'];
  }
      
  $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $fila['id'],'sites' => $fila['website'], 'icons' => $icon));
}

$fxlt_cont_site -> display();    
  
} 
} else {

$id = $_SESSION['user_id'];

$nSite = new Site($id);
$nSite->showProfile($id);
$resultSet = $nSite->getResult();

foreach ($resultSet as $fila) {
  $f_nac =  strtotime($fila['date']);
  $fecha = date("j F Y",$f_nac);
  $user_id = $fila['id'];
  $name = $fila['name'];
}

  if($user_id != ''){
    
    $cssclass = $_SESSION['n_col'];
    
    if(isset($_POST['selected']) && $_POST['selected'] !== ""){
      $cssclass = 'column-' .(intval($numCol));
    }
  
    $nSite->showSites($user_id);
   
    $fxlt_principal= new fxl_template('page.tpl');
    $fxlt_cont = new  fxl_template('profile_cont.tpl');

    $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
    $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
    $fxlt_cont_site2 = $fxlt_cont_site -> get_block('sitestemp');

    $fxlt_cont_pro -> assign('name',$fila['name']);
    $fxlt_cont_pro -> assign('date',$fecha);
    $fxlt_cont_pro -> assign('sex',$fila['sexo']);
    
    $resultSet2 = $nSite->getResult();
    
    foreach ($resultSet2 as $fila2) {
	if($fila2['icon']==''){
	  $icon="images/dummy.jpeg";
	}else{
	  $icon="images/".$fila2['icon'];
	}
	
      $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $fila2['id'],'sites' => $fila2['website'], 'icons' => $icon, 'cssclass' => $cssclass)); 
    }
    
    $fxlt_cont_pro -> assign('sitestempfather', $fxlt_cont_site);// bloque
    $fxlt_cont -> assign('profile',$fxlt_cont_pro);
    $fxlt_principal -> assign('name',$name);
    $fxlt_principal -> assign('content',$fxlt_cont );
  }
  $fxlt_principal -> display();
  
}
?>