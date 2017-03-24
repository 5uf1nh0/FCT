<?php
INCLUDE ('conexion.php');

$user = $_POST['user'];
$pass = $_POST['pass'];
$dob = $_POST['fecha'];
$sex = $_POST['sexo'];
$site=$_POST['site'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
 
$sql = "INSERT INTO users(name , password, date, sex) VALUES ('" . $user . "', '" . $pass . "','" . $dob . "','" . $sex . "');";
$res1 = $mysqli->query($sql);

$sqlid = "SELECT id FROM users WHERE `name` ='". $user ."' and `password`='". $pass ."';";
$res = $mysqli->query($sqlid);
$row = $res->fetch_assoc();

$sql2 = "INSERT INTO sites(iduser,website) VALUES (" . $row['id'] . ", '" . $site . "');";
$res2 = $mysqli->query($sql2);

//$row = $res->fetch_assoc();
//echo $row['_msg'];

header("Location: http://proyecto2.dev.com/login.php");

?>