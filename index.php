<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>HSS Final Project</title>

<!-- Import CSS and JS -->
<link rel="stylesheet" type="text/css" href="libs/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="libs/css/foundation.css">
<script src="libs/js/jquery-2.2.4.min.js"></script>
<script src="libs/js/jquery-ui.min.js"></script>
<script src="libs/js/bootstrap.min.js"></script>

<!-- My Own CSS and JS -->
<style>
body{
  background-image: url("libs/pics/summer.jpg");
  background-size:     cover;
  background-repeat:   no-repeat;
  background-position: center center;
}
#bingoCard td {
  border-radius: 25px;
  width: 100px;
  height: 150px;
  background-color: #e6e6e6;

  text-align: center;
  position: relative;
  font: normal 25px/1 Arial Black, Gadget, sans-serif;
  color: black;
  -o-text-overflow: ellipsis;
  text-overflow: ellipsis;
}

#targetNumDiv {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 250px;
  height: 250px;
  text-align: center;
  position: relative;
  font: normal 25px/1 Arial Black, Gadget, sans-serif;
  color: black;
  -o-text-overflow: ellipsis;
  text-overflow: ellipsis;
}
#targetNumSpan {
  font: normal 150px/1 Arial Black, Gadget, sans-serif;
}
#roundNumDiv {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 250px;
  height: 70px;
  text-align: center;
  position: relative;
  font: normal 25px/1 Arial Black, Gadget, sans-serif;
  color: black;
  -o-text-overflow: ellipsis;
  text-overflow: ellipsis;
}
#countDownDiv {
  border-radius: 25px;
  border: 2px solid #73AD21;
  padding: 20px;
  width: 250px;
  height: 70px;
  text-align: center;
  position: relative;
  font: normal 25px/1 Arial Black, Gadget, sans-serif;
  color: black;
  -o-text-overflow: ellipsis;
}
</style>

<script>
//Globale Variables
var targetNumPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var numberPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var wordPool = ['game','hot','fun','fruit','water','jump','run','swim','easy','beach','cheer','cool','fresh ','sea','ripe','rest','hit','happy','play','sun','feast','revel','roast','heat'];
var targetNum = 0;
var rounds = 0;
var roundsLimit = 20;
var setT;
var countDT;

//Sounds
var correct = new Audio("sounds/correct.wav");
var wrong = new Audio("sounds/wrong.wav");

//Instruction Popup
function callIns(){
  $('#insModal').modal('show');
}
//Randomize Bingo Card
function randomizeCard(){
  var tempNumPool = numberPool.slice(0);
  var tempWordPool = wordPool.slice(0);

  $("#bingoCard").find('td').each(function(){
    if($(this).attr('id') != "free"){
      var numIndex = Math.floor(Math.random() * tempNumPool.length);
      var wordIndex = Math.floor(Math.random() * tempWordPool.length);

      $(this).html("<span style=\"display:block\" id=\"numberSpan\">"+tempNumPool[numIndex]+"</span>" + "<span style=\"display:none\">"+tempWordPool[wordIndex]+"</span>");

      tempNumPool.splice(numIndex, 1);
      tempWordPool.splice(wordIndex, 1);
    }
  });
}

function emptyCard(){
  $("#bingoCard").find('td').each(function(){
    if($(this).attr('id') != "free"){
      $(this).html("");
      $(this).animate({
        backgroundColor: "#e6e6e6"
      }, 100);
    }
  });
}

function checkWinner(ctd,numberEle,wordEle){
  var cellNum = parseInt(ctd.attr('id'));
  var upNumRow = Math.floor(cellNum/5.1);
  var downNumRow = 5 - upNumRow - 1;
  var leftNumRow = Math.floor(cellNum%5.1);
  var rightNumRow = 5 - leftNumRow - 1;
  if(cellNum<=5){
    leftNumRow = Math.floor((cellNum+5)%5.1)
    rightNumRow = 5 - leftNumRow - 1;
  }

  //console.log("up down left right:" + upNumRow + " " + downNumRow + " "+ leftNumRow+" "+ rightNumRow);


  //Check Up
  var sArr = [ctd.attr('id')];
  for(i=1; i<=upNumRow; i++){
    var checkCellNum = cellNum - (5*i);
    var checkCellEle = $("#"+checkCellNum);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(checkCellEle.attr('id'));
      }
    }
  }
  //Check Down
  for(i=1; i<=downNumRow; i++){
    var checkCellNum = cellNum + (5*i);
    var checkCellEle = $("#"+checkCellNum);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(checkCellEle.attr('id'));
      }
    }
  }

  //Clear If Up Down No Bingo
  if(sArr.length == 5){
    mainGameWin(sArr);
    return;
  }
  sArr = [];

  //Check Left
  var sArr = [ctd.attr('id')];
  for(i=1; i<=leftNumRow; i++){
    var checkCellNum = cellNum - i;
    var checkCellEle = $("#"+checkCellNum);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(checkCellEle.attr('id'));
      }
    }
  }

  //Check Right
  for(i=1; i<=rightNumRow; i++){
    var checkCellNum = cellNum + i;
    var checkCellEle = $("#"+checkCellNum);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(checkCellEle.attr('id'));
      }
    }
  }

  //Clear If Left Right No Bingo
  if(sArr.length == 5){
    mainGameWin(sArr);
    return;
  }
  sArr = [];

  //Check Diagonals
  var leftD = [1, 7, "freeslot", 19, 25];
  var rightD = [5, 9, "freeslot", 17, 21];

  //Check Left Diagonal
  for(i=0; i < leftD.length; i++){
    var checkCellEle = $("#"+leftD[i]);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(leftD[i]);
      }
    }
  }

  //Clear If Left Diagonal No Bingo
  if(sArr.length == 5){
    mainGameWin(sArr);
    return;
  }
  console.log("left to right:" + sArr);
  sArr = [];

  //Check Right Diagonal
  for(i=0; i < rightD.length; i++){
    var checkCellEle = $("#"+rightD[i]);
    var wnumberEle = checkCellEle.find(":nth-child(1)");
    var wwordEle = checkCellEle.find(":nth-child(2)");
    if((wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block') || typeof checkCellEle.attr('id') == 'undefined'){
      if(typeof checkCellEle.attr('id') == 'undefined'){
        sArr.push("free");
      }
      else{
        sArr.push(rightD[i]);
      }
    }
  }

  //Clear If Left Diagonal No Bingo
  if(sArr.length == 5){
    mainGameWin(sArr);
    return;
  }
  console.log("right to left:" + sArr);
  sArr = [];

}

function assignClickable(){
  $(".bingonum").on("click",function(){
    var numberEle = $(this).find(":nth-child(1)");
    var wordEle = $(this).find(":nth-child(2)");
    //parseInt(numberEle.html()) == targetNum
    if(parseInt(numberEle.html()) == targetNum){
      correct.play();
      numberEle.css('display', 'none');
      wordEle.css('display', 'block');
      $(this).animate({
        backgroundColor: "#99ff99"
      }, 500);
      checkWinner($(this), numberEle, wordEle);
    }
    else{
      if(numberEle.css("display") == "block"){
        wrong.play();
        $(this).animate({
          backgroundColor: "#ff3333"
        }, 100);
        $(this).animate({
          backgroundColor: "#e6e6e6"
        }, 100);
      }
    }
  });
}
function countDown(){
  $('#countDownDiv').css('display', 'block');
  cTime = parseInt($('#countDownSpan').html());
  $('#countDownSpan').html(cTime-1);
  if(cTime == 0){
    return;
  }
  countDT = setTimeout(countDown , 1000);
}

function firstRound(){
  var targetNumIndex = Math.floor(Math.random() * targetNumPool.length);
  targetNum = targetNumPool[targetNumIndex];
  targetNumPool.splice(targetNumIndex, 1);
  $("#targetNumSpan").html(targetNum);
  rounds = rounds + 1;
  $("#roundNumSpan").html(rounds);
  //Start Count Down
  countDown();
}

function newRound(){
  if(rounds == roundsLimit+1){
    mainGameEnd();
    return;
  }
  setT = setTimeout(function(){
    var targetNumIndex = Math.floor(Math.random() * targetNumPool.length);
    targetNum = targetNumPool[targetNumIndex];
    targetNumPool.splice(targetNumIndex, 1);
    $("#targetNumSpan").html(targetNum);
    rounds = rounds + 1;
    $("#roundNumSpan").html(rounds);
    $("#countDownSpan").html("9");
    clearTimeout(countDT);
    countDown();
    newRound();
  },8000);
}

function mainGameStart(){
  //Clear What's left
  resetGame();
  //Disable Start Button
  $("#startButton").prop('disabled', true);
  //Randomize Every Thing
  randomizeCard();
  //Assign Clickable
  assignClickable();
  //Start round
  firstRound();
  newRound();
}

function mainGameEnd(){
  alert("Sorry You Didn't Win! Try Again");

  //Unbind clickable tds
  $(".bingonum").off("click");

  //Stop SetTimeout Loop
  clearTimeout(setT);
  clearTimeout(countDT);

  //Enable Start Button
  $("#startButton").prop('disabled', false);
}

function mainGameWin(sArr){
  alert("Congratulations! You Got A Bingo!");
  console.log(sArr);
  $("#"+sArr[0]).animate({backgroundColor: "#ffff80"}, 100);
  $("#"+sArr[1]).animate({backgroundColor: "#ffff80"}, 100);
  $("#"+sArr[2]).animate({backgroundColor: "#ffff80"}, 100);
  $("#"+sArr[3]).animate({backgroundColor: "#ffff80"}, 100);
  $("#"+sArr[4]).animate({backgroundColor: "#ffff80"}, 100);

  var one = $("#"+sArr[0]).find(":nth-child(2)").html();
  var two = $("#"+sArr[1]).find(":nth-child(2)").html();
  var three = $("#"+sArr[2]).find(":nth-child(2)").html();
  var four = $("#"+sArr[3]).find(":nth-child(2)").html();
  var five = $("#"+sArr[4]).find(":nth-child(2)").html();

  //Free Slot
  if(typeof one == 'undefined'){
    one = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }
  //Free Slot
  if(typeof two == 'undefined'){
    two = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }
  //Free Slot
  if(typeof three == 'undefined'){
    three = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }
  //Free Slot
  if(typeof four == 'undefined'){
    four = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }
  //Free Slot
  if(typeof five == 'undefined'){
    five = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }

  $("#poemtable").after("<tr>"+"<td>&nbsp;"+one+"&nbsp;</td>"+"<td>&nbsp;"+two+"&nbsp;</td>"+"<td>&nbsp;"+three+"&nbsp;</td>"+"<td>&nbsp;"+four+"&nbsp;</td>"+"<td>&nbsp;"+five+"&nbsp;</td>"+"</tr>");

  //Unbind clickable tds
  $(".bingonum").off("click");

  //Stop SetTimeout Loop
  clearTimeout(setT);
  clearTimeout(countDT);

  //Enable Start Button
  $("#startButton").prop('disabled', false);
}

function resetGame(){
  //Change Free Slot Color
  $('#free').animate({backgroundColor: "#99ff99"}, 100);
  //Empty Card
  emptyCard();
  //Re Initialize Global Variables
  targetNumPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
  targetNum = 0;
  rounds = 0;
  //Change html
  $("#targetNumSpan").html("Not Start Yet");
  $("#roundNumSpan").html("0");
  $("#countDownSpan").html("9");
}

$(document).ready(function(){
  //Game Start
  $("#startButton").click(function(){
    mainGameStart();
  });
});
</script>

</head>
<body onload="callIns()">
  <div class="row" data-equalizer data-equalize-on="center">
    <div class="large-12 columns">
      <h1>BINGOEM!</h1>
    </div>
  </div>
  <div class="row" data-equalizer data-equalize-on="medium">
    <div class="large-4 columns">
      <div>
        <button class="button expanded" id="insButton" onclick="callIns()">Instruction</button>
        <button class="button expanded" id="startButton">Game Start</button>
        <br>
        <div id="roundNumDiv">
          Round <span id="roundNumSpan">0</span>
        </div>
        <br>
        <div id="targetNumDiv">
          Find
          <br>
          <span id="targetNumSpan"></span>
        </div>
        <br>
        <div id="countDownDiv" style="display:none">
          Next in: <span id="countDownSpan" >9</span> sec
        </div>
        <br>
        <div>
          <span id="poemTitleSpan">Poem<span>
          <table id="poemtable">
          </table>
        </div>
      </div>
    </div>
    <div class="large-8 columns">
    <table id="bingoCard">
  	<tr>
  		<th colspan="5">Bingo Card</th>
  	</tr>
  	<tr>
  		<td id="1" class="bingonum">&nbsp;</td>
  		<td id="2" class="bingonum">&nbsp;</td>
  		<td id="3" class="bingonum">&nbsp;</td>
  		<td id="4" class="bingonum">&nbsp;</td>
  		<td id="5" class="bingonum">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="6" class="bingonum">&nbsp;</td>
  		<td id="7" class="bingonum">&nbsp;</td>
  		<td id="8" class="bingonum">&nbsp;</td>
  		<td id="9" class="bingonum">&nbsp;</td>
  		<td id="10" class="bingonum">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="11" class="bingonum">&nbsp;</td>
  		<td id="12" class="bingonum">&nbsp;</td>
  		<td id="free" style="background-color: #99ff99;">Free</td>
  		<td id="14" class="bingonum">&nbsp;</td>
  		<td id="15" class="bingonum">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="16" class="bingonum">&nbsp;</td>
  		<td id="17" class="bingonum">&nbsp;</td>
  		<td id="18" class="bingonum">&nbsp;</td>
  		<td id="19" class="bingonum">&nbsp;</td>
  		<td id="20" class="bingonum">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="21" class="bingonum">&nbsp;</td>
  		<td id="22" class="bingonum">&nbsp;</td>
  		<td id="23" class="bingonum">&nbsp;</td>
  		<td id="24" class="bingonum">&nbsp;</td>
  		<td id="25" class="bingonum">&nbsp;</td>
  	</tr>
    </table>
    </div>
  </div>
  <div id="insModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Instruction</h4>
        </div>
        <div class="modal-body">
          <p>How to play:</p>
            <ol>
              <li>Click &quot;Game Start&quot; button to start game</li>
              <li>Then take a look at &quot;FIND&quot; section on the left, you need to find that number in the bingo card and click that slot. But be careful, you need to find that number within 8 sec, otherwise it will disappear forever (Nooo!)</li>
              <li>In order to win, you need to have 5 words in a line, either vertically,&nbsp;horizontally, or&nbsp;diagonally.</li>
              <li>That &quot;Free&ldquo; slot is a give away.</li>
              <li>&nbsp;If you failed to have 5 words in a line within 20 rounds, you lose this round. But dont worry, you can always start it again. :)</li>
            </ol>
          <p>There, that&#39;s all the rules .</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Let's Go</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
</body>
</html>
