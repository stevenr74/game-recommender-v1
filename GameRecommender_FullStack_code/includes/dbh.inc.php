<?php
function OpenCon(){
	$dbServername = "localhost:3307";
	$dbUsername = "root";
	$dbPassword = "";
	$dbName = "games";

	$conn = new mysqli($dbServername, $dbUsername, $dbPassword, $dbName) or die("Connect failed: %s\n". $conn -> error);
	return $conn;
}

function CloseCon($conn){
	$conn -> close();
}
?>