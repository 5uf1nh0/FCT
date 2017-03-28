<?php
INCLUDE ('conexion.php');

$user = $_POST['user'];
$pass = $_POST['pass'];
$dob = $_POST['fecha'];
$sex = $_POST['sexo'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$sql = "INSERT INTO users(name , password, date, sex) VALUES ('" . $user . "', '" . $pass . "','" . $dob . "','" . $sex . "');";
$res = $mysqli->query($sql);
//$row = $res->fetch_assoc();
//echo $row['_msg'];

header("Location: http://proyecto1.dev.com");
    exit;

?>  
