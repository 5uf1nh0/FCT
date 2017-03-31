<?php
//session_unset();

ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

$fxlt = new fxl_template('login_page.tpl');
$fxlt_cont = new  fxl_template('login_cont.tpl');
$fxlt_cont_reg = $fxlt_cont->get_block('login');
$fxlt_cont->assign('login',$fxlt_cont_reg);

$_SESSION['user_id'] ='';

if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
 
  $user = $_REQUEST['user'];
  $pass = md5($_REQUEST['pass']);

  $mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);

  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

  $sql = "SELECT users.id,users.`name`
	  FROM users
	  WHERE users.`name` ='". $user ."' and users.`password`='". $pass ."';";

  $res = $mysqli->query($sql);
  $row = $res->fetch_assoc();

  $_SESSION['user_id'] = $row['id'];
  $_SESSION['user']=$row['name'];
  
}
  if((isset($_REQUEST['user'])&& isset($_REQUEST['pass'])) && $_SESSION['user_id']==''){
    echo 0;
  } else {
    if($_SESSION['user_id']!=''){
      echo 1;
    } else{
      
      $fxlt->assign('content',$fxlt_cont);
      $fxlt->display();
    }
  }
  

?>