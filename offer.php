<?php
require_once("util.php");

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
echo "<document>";

$sql = new MySQL_class("user_slf", "", "localhost", "stadt_land_fluss");

$sql->Connect();

$sql->SelectDB();

$username = $_POST['username'];
$challenged = $_POST['challenged'];

$sql->QueryItem("SELECT id from offers WHERE offerer = '$username'");

if($sql->data['id']>=1) {
	echo "<error>1</error>";
} else{
	echo "<error>0</error>";
	$sql->Insert("INSERT INTO offers (offerer, challenged) VALUES ('$username', '$challenged')");	
}
echo "</document>";
?>