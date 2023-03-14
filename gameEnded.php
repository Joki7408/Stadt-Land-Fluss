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


$sql->QueryItem("SELECT validated FROM words WHERE player='$username'");
$myPoints = $sql->data['validated'];

$sql->QueryItem("SELECT validated FROM words WHERE player='$opponent'");
$enemyPoints = $sql->data['validated'];

echo "<result>" . $myPoints . "|" . $enemyPoints . "</result>";

echo "</document>";
?>