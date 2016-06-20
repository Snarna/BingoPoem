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
table, td, th {
    border: 1px solid #ddd;
    text-align: mid;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    padding: 15px;
}
td:hover{background-color:#f5f5f5}
</style>

<script>
var numberPool = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24]
function randomizeCard(){
  $("#bingoCard").find('td').each(function(){
    $(this).html(numberPool[Math.floor(Math.random() * numberPool.length)]);
  });
}
</script>

</head>
<body>
  <div class="row">
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
		<td id="13">Free</td>
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

</body>
</html>
