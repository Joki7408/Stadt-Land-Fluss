<?php
	header('Access-Control-Allow-Origin: *');
	header("Content-Type: application/xml");
	echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	echo "<document>";	

	require_once("util.php");

	$sql = new MySQL_class("user_slf", "", "localhost", "stadt_land_fluss");

	$sql->Connect();
	$sql->SelectDB();
		
	$time   = time();
	$time_latest = $time - 6;

	$username = $_POST['username'];

	$sql->Query("SELECT username FROM users WHERE last_poll<'$time_latest'");
	
	for( $i=0; $i<$sql->rows; $i++ ) {
			$sql->Fetch($i);
			$row[$i] = $sql->data['username'];
	}
	
	for( $i=0; $i<$sql->rows; $i++) {
			$sql->Delete("DELETE FROM offers WHERE offerer='$row[$i]'");
			$sql->Delete("DELETE FROM games  WHERE player1='$row[$i]' OR player2='$row[$i]'");
			$sql->Delete("DELETE FROM words  WHERE player='$row[$i]'");
	}	
	
	$sql->Update("UPDATE users SET last_poll='$time' WHERE username='$username'");
	$sql->Query("SELECT username FROM users WHERE last_poll>='$time_latest'");
	$return_string = "<users>";
	
	if( $sql->rows > 0 ) {
		for( $i=0; $i<$sql->rows; $i++ ) {
			$sql->Fetch($i);
			$return_string .= $sql->data['username'] . "|";
		}
	}
	$return_string .= "</users>";
	$sql->Query("SELECT offerer FROM offers WHERE challenged = '$username'");
	$return_string .= "<offerers>";
	
	if( $sql->rows > 0 ) {
		for( $i=0; $i<$sql->rows; $i++ ) {
			$sql->Fetch($i);
			$return_string .= $sql->data['offerer'] . "|";
		}
	}
	$return_string .= "</offerers>";

	$sql->Query("SELECT player1 FROM games WHERE player1='$username' OR player2='$username'");
	$return_string .= "<opponent>";

	if( $sql->rows > 0 ) {
		$sql->Fetch(0);
		if( $sql->data['player1'] == $username ) {
			$sql->Query("SELECT player2 FROM games WHERE player1='$username' OR player2='$username'");
			$sql->Fetch(0);
			$opponent = $sql->data['player2'];
				$return_string .= $opponent;
		}
	}
	$sql->Query("SELECT player2 FROM games WHERE player1='$username' OR player2='$username'");
		
	if( $sql->rows > 0 ) {
		$sql->Fetch(0);
 		if( $sql->data['player2'] == $username ) {
			$sql->Query("SELECT player1 FROM games WHERE player1='$username' OR player2='$username'");
			$sql->Fetch(0);
			$opponent = $sql->data['player1'];
			$return_string .= $opponent;
		}
	}
	$return_string .= "</opponent>";

	$sql->QueryItem("SELECT finished FROM games");
	$return_string .= "<finished>";
	
	$finished = $sql->data['finished'];
	$return_string .= $finished;

	$return_string .= "</finished>";

	$sql->QueryItem("SELECT validated FROM games");
	$return_string .= "<validated>";
	
	$validated = $sql->data['validated'];
	$return_string .= $validated;

	$return_string .= "</validated>";

	echo "<error>" . $return_string . "</error>";
	echo "</document>";

?>