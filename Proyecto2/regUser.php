<?php
INCLUDE ('conexion.php');
INCLUDE ('DBHandler.php');
//INCLUDE ('User.php');
//INCLUDE ('Site.php');

$user = $_POST['user'];
$pass = md5($_POST['pass']);
$dob =  $_POST['anio']. "-" . $_POST['mes'] . "-" . $_POST['dia'];
$sex = $_POST['sexo'];
$site = $_POST['site'];
$icon = $_POST['icon'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
$db = new DBHandler($mysqli);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
 
$sql = "INSERT INTO users(name , password, date, sex) VALUES (?, ?, ?, ?);";

$db->add('s' , $user);
$db->add('s' , $pass);
$db->add('s' , $dob);
$db->add('i' , $sex);
$db->prepareStatement($sql);

$res1 = $db->query();
$db->clear();

$sqlid = "SELECT id FROM users WHERE `name` = ? and `password`= ?;";
$db->add('s' , $user);
$db->add('s' , $pass);
$db->prepareStatement($sqlid);

$res = $db->query();
$db->clear();

foreach ($res as $fila) {
    $user_id = $fila['id'];
}

$sql2 = "INSERT INTO sites(iduser,website,icon) VALUES (?, ?, ?);";

$db->add('i' , $user_id);
$db->add('s' , $site);
$db->add('s' , $icon);
$db->prepareStatement($sql2);

$res2 = $db->query();
$db->clear();

header("Location: http://proyecto2.dev.com/login.php");

?>