<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

$site= $_POST['site'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$id= $_SESSION['user_id'];

$sql = "INSERT INTO sites(iduser,website) VALUES (" . $id . ", '" . $site . "');";
$res = $mysqli->query($sql);

$sqlw="SELECT website FROM sites WHERE iduser = '".$id."'";
$resw = $mysqli->query($sqlw);

$fxlt_cont = new  fxl_template('profile_cont.tpl');
$fxlt_cont_pro = $fxlt_cont -> get_block('profile');
$fxlt_cont_site = $fxlt_cont_pro -> get_block('sites');
$fxlt_cont_aux = $fxlt_cont_pro -> get_block('sites');

while($row2 = $resw->fetch_assoc()) {
  foreach ($row2 as $value) {
      $fxlt_cont_site -> assign('sites',$value);
      $fxlt_cont_aux -> assign('sites', $fxlt_cont_site);
      $fxlt_cont_site -> clear();
  }
}
$fxlt_cont_aux -> display();

?>