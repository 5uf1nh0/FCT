 <?php
INCLUDE ('conexion.php');

require_once('fxl_template.inc.php');

$user = $_POST['user'];
$pass = $_POST['pass'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "SELECT `name`,`date`,sex FROM users WHERE `name` ='". $user ."' and `password`='". $pass ."';";

$res = $mysqli->query($sql);

$fxlt_cont = new  fxl_template('login_cont.tpl');
$fxlt_cont_log = $fxlt_cont->get_block('show');

 while($row = $res->fetch_assoc()) {
       $fxlt_cont_log -> assign('name',$row['name']);
       $fxlt_cont_log -> assign('date',$row['date']);
       $fxlt_cont_log -> assign('sex',$row['sex']);
    }
    
$fxlt_cont->assign('show',$fxlt_cont_log);

$fxlt_cont->assign('content',$fxlt_cont );
$fxlt_cont->display();


?>