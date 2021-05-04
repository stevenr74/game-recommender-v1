<?php
	include_once 'includes/dbh.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css">
<script src="image-picker.js"></script>
<link rel="stylesheet" type="text/css" href="image-picker.css">
<script src="grscript.js"></script>
<title>Game Recommender</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php
	$sql = "SELECT * FROM games;";
	$result = mysqli_query($conn, $sql);
	$resultCheck = mysqli_num_rows($result);
	
	if($resultCheck > 0){
		while($row = mysqli_fetch_assoc($result)) {
			echo $row['title'];
		}
	}
?>
<h1>Game Recommender</h1>
<p>Select some games that you like!</p>
<!-- Currently the value is used by the image selecter to identify which is selected/unselected so data
must be passed a different way
-->
<div id="content">
	<select class="image-picker" data-limit="3" multiple="multiple" id="pics">
		<option data-img-src="fortnite.jpg" value='1'>Fortnite</option>
		<option data-img-src="wow.jpg" value="2">World of Warcraft</option>
		<option data-img-src="witcher3.jpg" value="3">Witcher 3</option>
		<option data-img-src="codbo4.jpg" value="4">Call of Duty Black Ops 4</option>
		<option data-img-src="minecraft.png" value="5">Minecraft</option>
		<option data-img-src="stardew.jpg" value="6">Stardew Valley</option>
	</select>
</div>
<div id="option2">
</div>
<button class="button" id="submit">Submit</button>
</body>
</html>