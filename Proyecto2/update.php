<?php
ini_set('session.save_path', '/nasshare/webs/proyecto2/session');
session_start();

INCLUDE ('conexion.php');
require_once('fxl_template.inc.php');

$site= $_POST['site'];
$icon= $_POST['icon'];

$mysqli = new mysqli($dbhost , $dbusuario , $dbpassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$id= $_SESSION['user_id'];

$sql = "INSERT INTO sites(iduser,website,icon) VALUES (" . $id . ", '" . $site . "', '" . $icon . "');";
$res = $mysqli->query($sql);


$sqlw="SELECT sites.website,sites.icon FROM sites WHERE iduser = '".$id."'";
$resw = $mysqli->query($sqlw);

$fxlt_cont = new  fxl_template('profile_cont.tpl');
$fxlt_cont_pro = $fxlt_cont -> get_block('profile');
$fxlt_cont_site = $fxlt_cont_pro -> get_block('sites');
$fxlt_cont_aux = $fxlt_cont_pro -> get_block('sites');

while($row2 = $resw->fetch_assoc()) {
  
      $fxlt_cont_site -> assign('sites',$row2['website']);
      $fxlt_cont_site -> assign('icons',$row2['icon']);
      $fxlt_cont_aux -> assign('sites', $fxlt_cont_site);
      $fxlt_cont_site -> clear();
  
}
$fxlt_cont_aux -> display();

/*

<?php

$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";

  // if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

$pic_name = basename( $_FILES["fileToUpload"]["name"]);

$_POST['icon'] = $pic_name;

?>

*/


?>