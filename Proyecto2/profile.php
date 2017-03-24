<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

$user = $_POST['user'];
$pass = $_POST['pass'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT users.id,users.`name`,users.`date`,users.sex
	FROM users
	WHERE users.`name` ='". $user ."' and users.`password`='". $pass ."';";

$res = $mysqli->query($sql);
$row = $res->fetch_assoc();

$_SESSION['user_id'] = $row['id'];
if($row['id'] != ''){

	
$sqlw = "SELECT website
	 FROM sites
	 WHERE iduser =" . $row['id'] . " ;";

$resw = $mysqli -> query($sqlw);

$fxlt_principal= new fxl_template('profile_page.tpl');
$fxlt_cont = new  fxl_template('profile_cont.tpl');

$fxlt_cont_pro = $fxlt_cont -> get_block('profile');
$fxlt_cont_site = $fxlt_cont_pro -> get_block('sites');

$fxlt_cont_pro -> assign('name',$row['name']);
$fxlt_cont_pro -> assign('date',$row['date']);
$fxlt_cont_pro -> assign('sex',$row['sex']);

while($row2 = $resw->fetch_assoc()) {
  foreach ($row2 as $value) {
      $fxlt_cont_site -> assign('sites',$value);
      $fxlt_cont_pro -> assign('sites', $fxlt_cont_site);
      $fxlt_cont_site -> clear();
  }
}



$fxlt_cont -> assign('profile',$fxlt_cont_pro);
$fxlt_principal -> assign('name',$row['name']);
$fxlt_principal -> assign('content',$fxlt_cont );
$fxlt_principal -> display();
} else {
  echo 'sm ms';
}
?>
