<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rock, Paper, Scissors!</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<style>
* {
  box-sizing: border-box;
}

/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 50%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.new_game_button {
  width: 100%;
  height: 50px;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> <!--load jquery libraries so we can do ajax-->
</head>
<body>

<center><h2>Rock Paper Scissors</h2></center>
<div class="row">
  <div class="column" style="background-color:#aaa;">
    <center><h3>Player 1</h3></center>
    <div id="player1hand" style="height:200px; background-size: contain; background-repeat: no-repeat;background-position: center; ">
      &nbsp;
    </div>
  </div>
  <div class="column" style="background-color:#bbb;">
    <center><h3>Player 2</h3></center>
    <div id="player2hand" style="height:200px; background-size: contain; background-repeat: no-repeat;background-position: center; ">
      &nbsp;
    </div>
  </div>
</div>
<br>
<button onclick="newGame()" class="new_game_button">New Game</button>

<script>
function newGame() { //when function newGame is called, trigger newgame.php (this php file resets the both players hands to 0)
  $.ajax({
  method: "POST",
  url: "reset.php",
  });
}
var interval = 1000;  // 1000 = 1 second, 3000 = 3 seconds
function doAjax() {
    $.ajax({
            type: 'POST',
            url: 'gethands.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (data) {
                    $('#player1hand').css('background-image', 'url(' + data[0] + '.png)');
                    $('#player2hand').css('background-image', 'url(' + data[1] + '.png)');   
            },
            complete: function (data) {
                    // Schedule the next
                    setTimeout(doAjax, interval);
            }
    });
}
setTimeout(doAjax, interval);
</script>
</body>
</html>


