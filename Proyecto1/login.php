<?php
INCLUDE ('conexion.php');

$user = $_POST['user'];
$pass = $_POST['pass'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}
$sql = "SELECT `name`,`date`,sex FROM users WHERE `name` ='". $user ."' and `password`='". $pass ."';";

$res = $mysqli->query($sql);

 while($row = $res->fetch_assoc()) {
        //echo " Nombre: " . $row["name"]. " <br> "."Fecha de Nacimiento: " . $row["date"]. " <br> "."Genero: " . $row["sex"]. "";
        echo "<form>";
        echo "Nombre: " . $row["name"]. " <br> ";
        echo "Fecha de Nacimiento: " . $row["date"]. " <br> ";
        echo "Genero: " . $row["sex"]. " <br> ";
        echo "Sitio Web: <input type='text' name='web'>" . " <br> ";
        echo "<input type='submit' value='Modificar'>";
        echo "</form>";
    }
    
?>  
