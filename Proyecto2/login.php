<?php
session_unset();

require_once('fxl_template.inc.php');

$fxlt = new fxl_template('login_page.tpl');
$fxlt_cont = new  fxl_template('login_cont.tpl');
$fxlt_cont_reg = $fxlt_cont->get_block('login');
$fxlt_cont->assign('login',$fxlt_cont_reg);

//$fxlt_cont_reg2 = $fxlt_cont->get_block('show');
//$fxlt_cont->assign('show',$fxlt_cont_reg2);

$fxlt->assign('content',$fxlt_cont);
$fxlt->display();

?>