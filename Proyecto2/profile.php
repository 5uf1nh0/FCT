<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
INCLUDE ('DBHandler.php');
//INCLUDE ('User.php');
//INCLUDE ('Site.php');
require_once('fxl_template.inc.php');

//$nUser = new User();
//$nSite = new Site();

if (isset($_REQUEST['site'])){
  if ( $_REQUEST['site'] != ''){
    
    
    $site= $_POST['site'];
    $icon = $_FILES['file']['name'];
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

    $mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
    $db = new DBHandler($mysqli);
    
    if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    }
    //$nUser->setIdUser($_SESSION['user_id']);
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
	$sql = "INSERT INTO sites(iduser,website,icon) VALUES (?, ?, ?);";
	$db->add('i' , $id);
	$db->add('s' , $site);
	$db->add('s' , $icon);
	$db->prepareStatement($sql);
	$db->clear();
      }else{
	  //Inserta en la DB $site y una imagen predefinida si $icon viene vacia y se ha podido mover el file
	  $sql = "INSERT INTO sites(iduser,website,icon) VALUES (?, ?, ?);";
	  $db->add('i' , $id);
	  $db->add('s' , $site);
	  $db->add('s' , $icon);
	  $db->prepareStatement($sql);
	  $db->clear();
      }
    }


$sqlw = "SELECT sites.id,sites.website,sites.icon FROM sites WHERE iduser = ? and estado = ? ;";    

$db->add('i' , $id);
$db->add('i' , 1);
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
    $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $resw['id'],'sites' => $resw['website'], 'icons' => $icon));

    $fxlt_cont_site -> display();
  }
 } 
} else {

$id = $_SESSION['user_id'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
$db = new DBHandler($mysqli);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT users.id,users.`name`,users.`date`,genero.sexo
	FROM users,genero
	WHERE users.id= ? and users.sex = genero.id;";

$db->add('i' , $id);
$db->prepareStatement($sql);
$res = $db->query();
$db->clear();

foreach ($res as $fila) {
  $f_nac =  strtotime($fila['date']);
  $fecha = date("j F Y",$f_nac);
  $user_id = $fila['id'];
  $name = $fila['name'];
}

  if($user_id != ''){
    $est=1;
    $sqlw = "SELECT sites.id,sites.website,sites.icon
	    FROM sites
	    WHERE sites.iduser = ? and sites.estado = ?;";
    
    $db->add('i',$user_id);
    $db->add('i',$est);
    $db->prepareStatement($sqlw);
    
    $resw = $db->query();
    $db->clear();
  
  
    $fxlt_principal= new fxl_template('page.tpl');
    $fxlt_cont = new  fxl_template('profile_cont.tpl');

    $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
    $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');
    $fxlt_cont_site2 = $fxlt_cont_site -> get_block('sitestemp');

    $fxlt_cont_pro -> assign('name',$fila['name']);
    $fxlt_cont_pro -> assign('date',$fecha);
    $fxlt_cont_pro -> assign('sex',$fila['sexo']);

    foreach ($resw as $fila) {
	if($fila['icon']==''){
	  $icon="images/dummy.jpeg";
	}else{
	  $icon="images/".$fila['icon'];
	}
	
      $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $fila['id'],'sites' => $fila['website'], 'icons' => $icon)); 
    }
    
    $fxlt_cont_pro -> assign('sitestempfather', $fxlt_cont_site);// bloque
    $fxlt_cont -> assign('profile',$fxlt_cont_pro);
    $fxlt_principal -> assign('name',$name);
    $fxlt_principal -> assign('content',$fxlt_cont );
  }
  $fxlt_principal -> display();
  
}
?>