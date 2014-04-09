<html>
<head>
<title>Omicron Ratings - Season Graphs</title>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');

	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2','ratings'));    
	echo js(array(
		'excanvas',
		'rgraph/RGraph.common.core', 
		'rgraph/RGraph.common.dynamic',
		'rgraph/RGraph.common.key',
		'rgraph/RGraph.line'
	));
?>
</head>
<body>
<div id='container'>
<div id='content'>
<div id="graph">
</div>
<?php 


	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	// get list of conferences (arrConf)
	$sql = 'SELECT DISTINCT conf FROM teams WHERE conf <> "Non-Div I" ORDER BY conf';
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {$arrConf[$row['conf']] = array();}
	// for each conference:
	foreach($arrConf as $conf => &$arr) {
		//echo "<h3>" . $conf . "</h3>";
		// get list of all teams (arrTeams)
		$sql = 'SELECT * FROM teams WHERE conf = "' . $conf . '" ORDER BY team';
		$res = $db->query($sql);
		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$arr[$row['id']] = array ('team' => $row['team']);
		}
		
		// set up SELECT of conferences w/ accompanying JS array
		// set up SELECT of teams w/ populating array of each conference
		
		//echo '<p><label>' . $conf . ':</label></p>';
		//echo '<select>';
		//foreach($arr as $id => $tm) {
		//	echo '<option value="' . $id . '">' . $tm['team'] . '</option>';
		//}
		//echo '</select>';
		//echo '<hr/>';
	}
	
	$db->close();

?>

</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
<script type="text/javascript">
	$(document).ready(function() {

		loader = '<img src=\'assets/img/ajax-loader.gif\' class=\'loader\' alt=\'loading\'>';
		url = 'get_season.php?t=1';
		$.ajax ({
			url: url,
			type: 'get',
			success: function (data) {
				if (data !== null) {
					console.log(data);
					$('#graph').html(data);
					drawGraph();
				}
			},
			error: function (xhr, ajaxOptions, thrownError){
					alert(xhr.status + ' ' + thrownError);
					return false;
			},
			complete: function(data) {}
		});
	});
</script>
</html>