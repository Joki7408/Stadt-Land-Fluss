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
$opponent = $_POST['opponent'];
$value = $_POST['value'];

$sql->Update("UPDATE words SET validated = '$value' WHERE player ='$opponent'");
$sql->Update("UPDATE games SET validated = (SELECT validated from games WHERE player1 = '$opponent' OR player2 = '$opponent') + 1");


echo "<error>0</error>";

echo "</document>";
?>