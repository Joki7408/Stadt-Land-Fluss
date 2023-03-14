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
$letter = $_POST['letter'];

$sql->Insert("INSERT INTO games (player1, player2, letter, finished, validated) VALUES ('$username', '$opponent', '$letter', 0, 0)" );
$sql->Delete("DELETE FROM offers WHERE offerer='$opponent'" );
$sql->Delete("DELETE FROM offers WHERE offerer='$username'" );

echo "<error>0</error>";
echo "</document>";
?>