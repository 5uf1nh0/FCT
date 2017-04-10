<?php
//session_unset();

ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
INCLUDE ('DBHandler.php');
//INCLUDE ('User.php');
require_once('fxl_template.inc.php');

$fxlt = new fxl_template('page.tpl');
$fxlt_cont = new  fxl_template('login_cont.tpl');
$fxlt_cont_reg = $fxlt_cont->get_block('login');
$fxlt_cont->assign('login',$fxlt_cont_reg);

$_SESSION['user_id'] ='';
//$nUser = new User();
if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
  //$nUser->setName($_REQUEST['user']);
  //$nUser->setPass(md5($_REQUEST['pass']));
  $user = $_REQUEST['user'];
  $pass = md5($_REQUEST['pass']);
  
  $mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
  $db = new DBHandler($mysqli);

  if ($mysqli->connect_errno) {
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }

  $sql = "SELECT users.id,users.`name`
	  FROM users
	  WHERE users.`name` = ? and users.`password`= ?;";
  
  /*
  $db->add('s' , $nUser->getName();
  $db->add('s' , $nUser->getPass);
  */  
  $db->add('s' , $user);
  $db->add('s' , $pass);
  $db->prepareStatement($sql);
  
  $res = $db->query();
  $db->clear();
  
  foreach ($res as $fila) {
      $user_id = $fila['id'];
      $user_name = $fila['name']; 
  }

  $_SESSION['user_id'] = $user_id;
  $_SESSION['user'] = $user_name;
  
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