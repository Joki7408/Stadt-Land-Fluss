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

$sql->QueryItem("SELECT password FROM users WHERE username='$username'");
$hash = $sql->data['password'];

if (password_verify($password, $hash)){
	echo "<error>0</error>";
} else{
	echo"<error>1</error>";
}
echo "</document>";
?>