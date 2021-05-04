<?php
	include 'includes/dbh.inc.php';
	$conn = OpenCon();
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		$sql = "SELECT * FROM gamechoices;";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		$games = array();
		if($resultCheck > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$games[] = $row;
			}
		}
		
		CloseCon($conn);
	?>
</body>
</html>