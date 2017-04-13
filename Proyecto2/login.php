<?php

ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('User.php');
require_once('fxl_template.inc.php');

$fxlt = new fxl_template('page.tpl');
$fxlt_cont = new  fxl_template('login_cont.tpl');
$fxlt_cont_reg = $fxlt_cont->get_block('login');
$fxlt_cont->assign('login',$fxlt_cont_reg);

$_SESSION['user_id'] ='';

if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
  $user = $_REQUEST['user'];
  $pass = md5($_REQUEST['pass']);

  $nUser = new User($user,$pass);
  
  $_SESSION['user_id'] = $nUser->getId();
  $_SESSION['user'] = $nUser->getName();

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