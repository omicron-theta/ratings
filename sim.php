<html>
<head>
</head>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');

	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_bb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	$sql = "SELECT id, team FROM teams;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$teams[] = $row;
	}
	
	
		
	
	$db->close();
*/
?>
<body>
<?php
	echo "<p>";
	echo $_GET['v'];
	echo "</p>";
	echo "<p>";
	echo $_GET['h'];
	echo "</p>";
?>

</body>
</html>