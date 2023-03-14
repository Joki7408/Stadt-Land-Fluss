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


$sql->QueryItem("SELECT letter FROM games WHERE player1 ='$username' OR player2='$username'" );

$letter = $sql->data['letter'];
if($letter!='') {
    
	echo "<letter>" . $letter . "</letter>";

}
echo "</document>";
?>