<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>HSS Final Project</title>

<!-- Import CSS and JS -->
<link rel="stylesheet" type="text/css" href="libs\css\foundation.css">
<script src="libs\js\jquery-3.0.0.min.js"></script>

<!-- My Own CSS and JS -->
<style>
table, td, th{
  border: 1px solid #ddd;
  text-align: mid;
}
table {
    border-collapse: collapse;
    width: 100%;
}
td {
  width: 100px;
  position: relative;
}
td:after {
  content: '';
  display: block;
  margin-top: 100px;
}
td:hover{background-color:#f5f5f5}
</style>

<script>
//Globale Number Pool
var numberPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24];
var wordPool = ['a','ab','abc','ad','ae','af','ag','ah','ai','aj','af','ad','aas','ads','af','ac','ae','aq','ab','aq','ad','as','aopop','ah',]

//Randomize Bingo Card
function randomizeCard(){
  var tempNumPool = numberPool.slice(0);
  var tempWordPool = wordPool.slice(0);

  $("#bingoCard").find('td').each(function(){
    if($(this).attr('id') != "free"){
      var numIndex = Math.floor(Math.random() * tempNumPool.length);
      var wordIndex = Math.floor(Math.random() * tempWordPool.length);

      $(this).html(tempNumPool[numIndex] + "Hidden:" + tempWordPool[wordIndex]);

      tempNumPool.splice(numIndex, 1);
      tempWordPool.splice(wordIndex, 1);
    }
  });
}
</script>

</head>
<body>
  <div class="row">
    <h1>Title Should Go Here</h1>
  </div>
  <div class="row">
    <div class="large-8 columns">
      <table id="bingoCard">
  	<tr>
  		<th colspan="5">!Bingoem!</th>
  	</tr>
  	<tr>
  		<td id="1">&nbsp;</td>
  		<td id="2">&nbsp;</td>
  		<td id="3">&nbsp;</td>
  		<td id="4">&nbsp;</td>
  		<td id="5">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="6">&nbsp;</td>
  		<td id="7">&nbsp;</td>
  		<td id="8">&nbsp;</td>
  		<td id="9">&nbsp;</td>
  		<td id="10">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="11">&nbsp;</td>
  		<td id="12">&nbsp;</td>
  		<td id="free">Free</td>
  		<td id="14">&nbsp;</td>
  		<td id="15">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="16">&nbsp;</td>
  		<td id="17">&nbsp;</td>
  		<td id="18">&nbsp;</td>
  		<td id="19">&nbsp;</td>
  		<td id="20">&nbsp;</td>
  	</tr>
  	<tr>
  		<td id="21">&nbsp;</td>
  		<td id="22">&nbsp;</td>
  		<td id="23">&nbsp;</td>
  		<td id="24">&nbsp;</td>
  		<td id="25">&nbsp;</td>
  	</tr>
    </table>
    </div>
    <div class="large-4 columns">
      <div>
        <h4>This place is a place holder</h4>
      </div>
    </div>
  </div>

</body>
</html>
