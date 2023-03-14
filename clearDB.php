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

$sql->Delete("DELETE FROM offers WHERE offerer='$username' OR challenged='$username'");
$sql->Delete("DELETE FROM games  WHERE player1='$username' OR player2='$username'");
$sql->Delete("DELETE FROM words  WHERE player='$username'");

echo "<error>0</error>";
echo "</document>";
?>