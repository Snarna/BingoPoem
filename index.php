<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>HSS Final Project</title>

<!-- Import CSS and JS -->
<link rel="stylesheet" type="text/css" href="libs\css\foundation.css">
<script src="libs\js\jquery-3.0.0.min.js"></script>
<script src="libs\js\jquery-ui.min.js"></script>
<script src="libs\js\TweenMax.min.js"></script>
<script src="libs\js\jquery.gsap.min.js"></script>

<!-- My Own CSS and JS -->
<style>
#bingoCard td {
  border-radius: 25px;
  width: 100px;
  height: 150px;
  background-color: #e6e6e6;
  text-align: center;
  position: relative;
}
</style>

<script>
//Globale Variables
var targetNumPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var numberPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var wordPool = ['a','ab','abc','ad','ae','af','ag','ah','ai','aj','af','ad','aas','ads','af','ac','ae','aq','ab','aq','ad','as','aopop','ah',];
var targetNum = 0;
var rounds = 0;
var roundsLimit = 15;
var systemSignal = 0;

//Sounds
var correct = new Audio("sounds/correct.wav");
var wrong = new Audio("sounds/wrong.wav");

//Randomize Bingo Card
function randomizeCard(){
  var tempNumPool = numberPool.slice(0);
  var tempWordPool = wordPool.slice(0);

  $("#bingoCard").find('td').each(function(){
    if($(this).attr('id') != "free"){
      var numIndex = Math.floor(Math.random() * tempNumPool.length);
      var wordIndex = Math.floor(Math.random() * tempWordPool.length);

      $(this).html("<span style=\"display:block\">"+tempNumPool[numIndex]+"</span>" + "<span style=\"display:none\">"+tempWordPool[wordIndex]+"</span>");

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
    systemSignal = 1;
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
    systemSignal = 1;
    mainGameWin(sArr);
    return;
  }
  sArr = [];

  //Check Diagonals
  var leftD = [1, 7, 19, 25];
  var rightD = [5, 9, 17, 21];

  //Check Left Diagonal
  for(i=0; i < leftD.length; i++){
    var wnumberEle = $("#"+leftD[i]).find(":nth-child(1)");
    var wwordEle = $("#"+leftD[i]).find(":nth-child(2)");
    if(wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block'){
      sArr.push(leftD[i]);
    }
  }

  //Clear If Left Diagonal No Bingo
  if(sArr.length == 4){
    systemSignal = 1;
    mainGameWin(sArr);
    return;
  }
  sArr = [];

  //Check Left Diagonal
  for(i=0; i < rightD.length; i++){
    var wnumberEle = $("#"+rightD[i]).find(":nth-child(1)");
    var wwordEle = $("#"+rightD[i]).find(":nth-child(2)");
    if(wnumberEle.css('display') == 'none' && wwordEle.css('display') == 'block'){
      sArr.push(rightD[i]);
    }
  }

  //Clear If Left Diagonal No Bingo
  if(sArr.length == 4){
    systemSignal = 1;
    mainGameWin(sArr);
    return;
  }
  sArr = [];

}

function assignClickable(){
  $(".bingonum").on("click",function(){
    var numberEle = $(this).find(":nth-child(1)");
    var wordEle = $(this).find(":nth-child(2)");
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

function firstRound(){
  var targetNumIndex = Math.floor(Math.random() * targetNumPool.length);
  targetNum = targetNumPool[targetNumIndex];
  targetNumPool.splice(targetNumIndex, 1);
  $("#targetNumSpan").html(targetNum);
  rounds = rounds + 1;
  $("#roundNumSpan").html(rounds);
}

function newRound(){
  if(systemSignal == 1){
    return;
  }
  if(rounds == roundsLimit+1){
    mainGameEnd();
    return;
  }
  setTimeout(function(){
    var targetNumIndex = Math.floor(Math.random() * targetNumPool.length);
    targetNum = targetNumPool[targetNumIndex];
    targetNumPool.splice(targetNumIndex, 1);
    $("#targetNumSpan").html(targetNum);
    rounds = rounds + 1;
    $("#roundNumSpan").html(rounds);
    newRound();
  },8000);
}

function mainGameStart(){
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
  //Enable Start Button
  $("#startButton").prop('disabled', false);
}

function mainGameWin(sArr){
  alert("Congratulations! You Got A Bingo!");
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
  if(typeof three == 'undefined'){
    three = "of";
    $("#free").animate({backgroundColor: "#ffff80"}, 100);
  }
  $("#poemtable").after("<tr>"+"<td>&nbsp;"+one+"&nbsp;</td>"+"<td>&nbsp;"+two+"&nbsp;</td>"+"<td>&nbsp;"+three+"&nbsp;</td>"+"<td>&nbsp;"+four+"&nbsp;</td>"+"<td>&nbsp;"+five+"&nbsp;</td>"+"</tr>");

  //Unbind clickable tds
  $(".bingonum").off("click");
  //Enable Start Button
  $("#startButton").prop('disabled', false);
}

function resetGame(){
  //Change System Signal
  systemSignal = 0;
  //Empty Card
  emptyCard();
  //Re Initialize Global Variables
  targetNumPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
  targetNum = 0;
  rounds = 0;
  //Change html
  $("#targetNumSpan").html("Not Start Yet");
  $("#roundNumSpan").html("0");
}

$(document).ready(function(){
  //Game Start
  $("#startButton").click(function(){
    mainGameStart();
  });
});
</script>

</head>
<body>
  <div class="row">
    <h1>Title Should Go Here</h1>
  </div>
  <div class="row">
    <div class="large-4 columns">
      <div>
        <h4>Control Section</h4>
        <button class="button expanded" id="startButton">Start</button>
        <br>
        <div id="targetNumDiv">
          Target Number: <span id="targetNumSpan">Not Start Yet</span>
        </div>
        <div id="roundNumDiv">
          Rounds: <span id="roundNumSpan">0</span>
        </div>
        <br>
        <div>
          your poem:
          <table id="poemtable">
          </table>
        </div>
      </div>
    </div>
    <div class="large-8 columns">
    <table id="bingoCard">
  	<tr>
  		<th colspan="5">!Bingoem!</th>
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

</body>
</html>
