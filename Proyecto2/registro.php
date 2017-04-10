 <?php
 
require_once('fxl_template.inc.php');

$fxlt = new fxl_template('page.tpl');
$fxlt_cont = new  fxl_template('registro_cont.tpl');
$fxlt_cont_reg = $fxlt_cont->get_block('signup');
$fxlt_cont->assign('signup',$fxlt_cont_reg);

$fxlt->assign('content',$fxlt_cont );
$fxlt->display();

?>
