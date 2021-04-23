<?php
function initialize(){ 
    $conn = pg_connect("host=localhost port=5432 dbname=roshambo user=foo password=bar"); 
    //databse credentials so the code knows which databse to connect to and what username and passowrd to use.

    //only for the first time to run, we are creating a row to hold the information of the player's hands
    //since the column is structured,
    //  id  |  player_1_hand | player_2_hand
    //  1   |      0         |       0    
    // 
    // we are deciding that 0 value in player's hand means no rock, paper or scissors chosen yet
    // and since the column id is set up to automatically increment by one as new rows pop up, we just insert DEFAULT to let the machine know, please fill in with the approriate number based on the amount of rows
    // then we insert the value 0  for both player_1_hand and player_2_hand
    // this is how we arrive with VALUES (DEFAULT,'0','0')
    $query = "INSERT INTO game VALUES (DEFAULT,'0','0')";
    $result = pg_query($query) or die("Cannot execute query: $query\n"); //execute the query or die. die means if it errors out, print the message "Cannot execute query: $query" to let us know there is an issue.
}

function get_player_hands(){
    $conn = pg_connect("host=localhost port=5432 dbname=roshambo user=foo password=bar"); 
    $query = "select * from game where id = 1";
    $rs = pg_query($conn, $query) or die("Cannot execute query: $query\n");
    while ($row = pg_fetch_row($rs)) { //gets the values of each column of the row
    //since the column is structured,
    //  id  |  player_1_hand | player_2_hand
    //  1   |      rock      |       0    
    //
    // $row[0] is the value for the column 'id'
    // $row[1] is the value for the column 'player_1_hand'
    // $row[2] is the value for the column 'player_2_hand'
        $player_1_hand = $row[1];
        $player_2_hand = $row[2];
  //echo "$row[0] $row[1] $row[2]\n"; 
    }
    return array($player_1_hand,$player_2_hand);
}
/* //<----- remove this "slash star" start comment and "star slash" end comment boundaries to test the function 
//how to use the et_player_hands() function:
list($player_1_hand,$player_2_hand) = get_player_hands(); //since get_player_hands() returns an array of 2 values, we use list in order to individually assign the values to variables: $player_1_hand and $player_2_hand 
echo $player_1_hand; //now we can output player 1's hand which is stored in $player_1_hand;
*/

function update_hand($player_number,$hand){ //we will use this function when an arduino signal comes in. We will use date the db the value of the corresponding player's hand based on the data arduino sends to the website.
    $player = 'player_'.$player_number.'_hand'; //this forms the column name based on the $player_number value. So if $player_number = 1, then the $player variable will contain player_1_hand.
    $conn = pg_connect("host=localhost port=5432 dbname=roshambo user=foo password=bar"); 
    $query = "update game set ".$player." = '".$hand."' where id = 1"; //$hand's value should be 'rock', 'paper' or 'scissors' or other which means no hand.
    $rs = pg_query($conn, $query) or die("Cannot execute query: $query\n");
}
 /*//<----- remove this "slash star" start comment and "star slash" end comment boundaries to test the function 
update_hand(1,'paper');
list($player_1_hand,$player_2_hand) = get_player_hands(); //get players hands to check the update
echo $player_1_hand; //print player 1's hand on the screen
*/

function reset_hands(){ //updates both player_1_hand and player_2_hand to 0, meaning none or no hands
    $conn = pg_connect("host=localhost port=5432 dbname=roshambo user=foo password=bar"); 
    $query = "update game set player_1_hand = '0', player_2_hand = '0' where id = 1"; //$hand's value should be 'rock', 'paper' or 'scissors' or other which means no hand.
    $rs = pg_query($conn, $query) or die("Cannot execute query: $query\n");

}
/* //<----- remove this "slash star" start comment and "star slash" end comment boundaries to test the function 
reset_hands();
list($player_1_hand,$player_2_hand) = get_player_hands(); //get players hands to check the reset hands
echo '<br> new hands after reset: '.$player_1_hand.' '.$player_2_hand; //both hands should show 0.
*/


//pg_close($conn); //close connection to the database

?>