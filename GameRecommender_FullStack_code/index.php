<?php
	include 'grscript.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="style.css?<?php echo time(); ?>" />
<script src="image-picker.js"></script>
<link rel="stylesheet" type="text/css" href="image-picker.css">
<!-- <script src="grscript.php"></script> -->
<title>Game Recommender</title>
</head>
<body>
<div class="topbar">
	<span class="openbtn" onclick="openNav()">&#9776;</span>
	<h1>Game Recommender</h1>

</div>
<div id="nav_container" class="nav_container" >
	<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
	<a href="#">About</a>
	<a href="#">Contact</a>
</div>

<p>Select some games that you like!</p>
<!-- Currently the value is used by the image selecter to identify which is selected/unselected so data
must be passed a different way
-->
<div id="devices">
</div>
<div id="option1">
</div>
<section id="instructionsForSecondChoice">
</section>
<div id="option2">
</div>
<section id="recommends">
</section>
<section id="displayResults">
</section>
<div id="option3">
</div>
<section id="secondGenre">
</section>
<div id="option4">
</div>
<button class="button" id="submit">Submit</button>
<button class="button" id="refresh">Refresh</button>
</body>
</html>