<?php
//Allow Headers
header('Access-Control-Allow-Origin: *');
//print_r(json_encode($_FILES));
//str_replace("world","Peter","Hello world!");
$arquivo = date('Ymdhis').'.jpg';
//echo "<br>";
//echo date('Y-m-d h:i:s');
//$new_image_name = str_replace('.jpg','',urldecode($_FILES["file"]["name"])).".jpg";
$new_image_name = str_replace('.jpg','',urldecode($_FILES["file"]["name"])).$arquivo;
//Move your files into upload folder
move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$new_image_name);
?>