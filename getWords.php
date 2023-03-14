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

sleep(1);

$sql->Query("SELECT * FROM words WHERE player='$player'");
$return_string .= "<words>";

if( $sql->rows > 0 ) {
    for( $i=0; $i<$sql->rows; $i++ ) {
        $sql->Fetch($i);
        $return_string .= $sql->data['word1'] . "|";
        $return_string .= $sql->data['word2'] . "|";
        $return_string .= $sql->data['word3'] . "|";
        $return_string .= $sql->data['word4'] . "|";
        $return_string .= $sql->data['word5'];
    }
}
$return_string .= "</words>";

echo $return_string;

$sql->Delete("DELETE FROM offers WHERE offerer='$player' OR challenged='$player'");

sleep(1);

echo "</document>";
?>