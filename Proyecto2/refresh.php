<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('Site.php');
require_once('fxl_template.inc.php');

$id = $_SESSION['user_id'];


$cssclass = 'column-' .$_POST['selected'];

if(isset($_POST['selected']) && $_POST['selected'] !== ""){
  $numCol = $_POST['selected'];
  $cssclass = 'column-' .(intval($_POST['selected']));
  $_SESSION['n_col'] = 'column-' . $numCol;
}

  $fxlt_cont = new  fxl_template('profile_cont.tpl');
  $fxlt_cont_pro = $fxlt_cont -> get_block('profile');
  $fxlt_cont_site = $fxlt_cont_pro -> get_block('sitestempfather');

  $nSite = new Site($id);
  $nSite->showSites($id);
  $reSet = $nSite->getResult();

  foreach ($reSet as $fila) {
    if($fila['icon']==''){
      $icon="images/dummy.jpeg";
    }else{
      $icon="images/".$fila['icon'];
    }
	
    $fxlt_cont_site->fill_block('sitestemp' , array('rowID' => $fila['id'],'sites' => $fila['website'], 'icons' => $icon, 'cssclass' => $cssclass));
  }

  $fxlt_cont_site -> display();

?>