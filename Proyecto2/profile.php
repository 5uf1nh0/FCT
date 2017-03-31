<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

if (isset($_REQUEST['site'])){
  if ( $_REQUEST['site'] != ''){
    $site= $_POST['site'];
    $icon = $_FILES['file']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    $mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);

    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }

    $id= $_SESSION['user_id'];

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
	$sql = "INSERT INTO sites(iduser,website,icon) VALUES (" . $id . ", '" . $site . "', '" . $icon . "');";
	$res = $mysqli->query($sql);
      }else{
	  //Inserta en la DB $site y una imagen predefinida si $icon viene vacia y se ha podido mover el file
	  $sql = "INSERT INTO sites(iduser,website,icon) VALUES (" . $id . ", '" . $site . "', '" . $icon . "');";
	  $res = $mysqli->query($sql);
      }
    }


    $sqlw = "SELECT sites.id,sites.website,sites.icon FROM sites WHERE iduser = '".$id."'";
    $resw = $mysqli->query($sqlw);

    $fxlt_cont = new  fxl_template('profile_cont.tpl');
    $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
    $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
    //$fxlt_cont_aux = $fxlt_cont_site -> get_block('sitestemp');

    while($row2 = $resw->fetch_assoc()) {

      if($row2['icon']==''){
	$icon="images/dummy.jpeg";
      }else{
	$icon="images/".$row2['icon'];
      }
      /*$fxlt_cont_site -> assign('sites',$row2['website']);
      $fxlt_cont_site -> assign('icons',$icon);
      $fxlt_cont_aux -> assign('sitestemp', $fxlt_cont_site);
      $fxlt_cont_site -> clear();*/
      $fxlt_cont_site->fill_block('sitestemp' , array('sites' => $row2['website'], 'icons' => $icon));

    }
    $fxlt_cont_site -> display();
  }
} else {

$id = $_SESSION['user_id'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT users.id,users.`name`,users.`date`,genero.sexo
	FROM users,genero
	WHERE users.id= ". $id . " and users.sex = genero.id;";

$res = $mysqli->query($sql);
$row = $res -> fetch_assoc();
$f_nac =  strtotime($row['date']);
$fecha = date("j F Y",$f_nac);

  if($row['id'] != ''){

    $sqlw = "SELECT website,icon
	    FROM sites
	    WHERE iduser =" . $row['id'] . " ;";

    $resw = $mysqli -> query($sqlw);
  }
  
  $fxlt_principal= new fxl_template('profile_page.tpl');
  $fxlt_cont = new  fxl_template('profile_cont.tpl');

  $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
  $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
$fxlt_cont_site2 = $fxlt_cont_site -> get_block('sitestemp');

  $fxlt_cont_pro -> assign('name',$row['name']);
  $fxlt_cont_pro -> assign('date',$fecha);
  $fxlt_cont_pro -> assign('sex',$row['sexo']);

  while($row2 = $resw->fetch_assoc()) {
    $icon = "images/".$row2['icon'];
  
    if($row2['icon']==''){
      $icon = "images/dummy.jpeg";
    }
    
    /*$fxlt_cont_site2 -> assign('sites',$row2['website']);// variables
    $fxlt_cont_site2 -> assign('icons',$icon);
    $fxlt_cont_site -> assign('sitestemp', $fxlt_cont_site2);// bloque
    $fxlt_cont_site2 -> clear();*/
    
    $fxlt_cont_site->fill_block('sitestemp' , array('sites' => $row2['website'], 'icons' => $icon)); 
  }
$fxlt_cont_pro -> assign('sitestempfather', $fxlt_cont_site);// bloque

  $fxlt_cont -> assign('profile',$fxlt_cont_pro);
  $fxlt_principal -> assign('name',$row['name']);
  $fxlt_principal -> assign('content',$fxlt_cont );
  $fxlt_principal -> display();
}
?>