<?php
//this file is used to get the data that the aduinos will send
//2 data will be needed: player number 1 or 2, hand = 1, 2, 3 scissors or 0.
//get data from arduino
$player = $_POST["player"];
$hand = $_POST["hand"];

if (isset($player) && isset($hand)){
	if ($player ==1 || $player==2){
		include 'game_server.php';
		switch($hand){
			case 1: $hand = "rock"; break;
			case 2: $hand = "paper"; break;
			case 3: $hand = "scissors"; break;
			default: $hand = "0";
		}
		update_hand($player,$hand);
	}
}

?>