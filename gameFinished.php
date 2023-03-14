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

$sql->Update("UPDATE games SET finished = 1 WHERE player1 ='$username' or player2 ='$username'" );
echo "<error>0</error>";

echo "</document>";
?>