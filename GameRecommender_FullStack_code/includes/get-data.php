<?php
	include 'includes/dbh.inc.php';
	
	function GetData(){	
		$conn = OpenCon();
		$sql = "SELECT * FROM gamechoices;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		$games = array();
		if($resultCheck > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$games[] = $row;
			}
		}
		/*	
		foreach ($games as $game){
			echo $game['title'];
		}
		*/
		//print_r($games[1]['title']);
		CloseCon($conn);
		return $games;
	}
?>