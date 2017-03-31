<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

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
	    //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
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
$fxlt_cont_site = $fxlt_cont_pro -> get_block('sites');
$fxlt_cont_aux = $fxlt_cont_pro -> get_block('sites');

while($row2 = $resw->fetch_assoc()) {
  $icon="images/".$row2['icon'];
  
  if($row2['icon']==''){
    $icon="images/dummy.jpeg";
  }
  
      $fxlt_cont_site -> assign('sites',$row2['website']);
      $fxlt_cont_site -> assign('icons',$icon);
      $fxlt_cont_aux -> assign('sites', $fxlt_cont_site);
      $fxlt_cont_site -> clear();
  
}
$fxlt_cont_aux -> display();

?>