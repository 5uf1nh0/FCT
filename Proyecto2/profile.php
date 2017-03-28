<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

$id = $_SESSION['user_id'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT users.id,users.`name`,users.`date`,genero.sexo
	FROM users,genero
	WHERE users.id= ". $id . " and users.sex = genero.id;";

$res = $mysqli->query($sql);
$row = $res->fetch_assoc();

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
  $fxlt_cont_site = $fxlt_cont_pro -> get_block('sites');

  $fxlt_cont_pro -> assign('name',$row['name']);
  $fxlt_cont_pro -> assign('date',$fecha);
  $fxlt_cont_pro -> assign('sex',$row['sexo']);

  while($row2 = $resw->fetch_assoc()) {
    
	$fxlt_cont_site -> assign('sites',$row2['website']);// variables
	$fxlt_cont_site -> assign('icons',$row2['icon']);
	$fxlt_cont_pro -> assign('sites', $fxlt_cont_site);// bloque
	
	$fxlt_cont_site -> clear();
    
  }

  $fxlt_cont -> assign('profile',$fxlt_cont_pro);
  $fxlt_principal -> assign('name',$row['name']);
  $fxlt_principal -> assign('content',$fxlt_cont );
  $fxlt_principal -> display();

?>