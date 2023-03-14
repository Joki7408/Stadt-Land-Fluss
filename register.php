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
$password = $_POST['password'];
$hash = password_hash($password, PASSWORD_DEFAULT);

$sql->QueryItem("SELECT id from users WHERE username='$username'");

if($sql->data['id']>=1) {
	echo "<error>1</error>";
} else{
	echo "<error>0</error>";
	$sql->Insert("INSERT INTO users (username, password) VALUES ('$username', '$hash')");	
}
echo "</document>";
?>