<?php
require_once("util.php");
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/xml");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
echo "<document>";

$sql = new MySQL_class("user_slf", "", "localhost", "stadt_land_fluss");

$sql->Connect();
$sql->SelectDB();

$player = $_POST['player'];
$word1 = $_POST['word1'];
$word2 = $_POST['word2'];
$word3 = $_POST['word3'];
$word4 = $_POST['word4'];
$word5 = $_POST['word5'];


$sql->Insert("INSERT INTO words (player, word1, word2, word3, word4, word5, validated) VALUES ('$player', '$word1', '$word2', '$word3', '$word4', '$word5', 0)" );

echo "<error>0</error>";

echo "</document>";

?>