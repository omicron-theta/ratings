<html>
<head>
<title>Omicron Ratings</title>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');

	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2','tablesorter','tablesorter.widgets','ratings'));
?>
	<script type="text/javascript">
		$(document).ready(function() {
		
			$("#ratings").tablesorter({
				/*widgets: ['filter'],
				widgetOptions: {
					filter_columnFilters: true,
					filter_functions: {
						2: true
					}
				}, */
				sortList: [[11,1]]
			});
		});
	</script>

</head>
<body>
<div id='container'>
<div id='content'>
<?php 

	set_time_limit(0);
	$sql = "SELECT t.*, r.*, wr.oRtg_adj w_oRtg_adj, wr.dRtg_adj w_dRtg_adj
	FROM teams t INNER JOIN ratings r ON t.id = r.team_id 
	INNER JOIN wei_ratings wr ON t.id = wr.team_id ORDER BY t.team ASC;";
	
	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$school = $row['id'];
		if (!isset(${$school})) {
			${$school} = new Rating($row['team']);
			${$school}->conf = $row['conf'];
			${$school}->pace = $row['pace'];
			${$school}->oRtg= $row['oRtg'];
			${$school}->dRtg= $row['dRtg'];
			${$school}->oRtg_adj= $row['oRtg_adj'];
			${$school}->dRtg_adj= $row['dRtg_adj'];
			${$school}->w_oRtg_adj= $row['w_oRtg_adj'];
			${$school}->w_dRtg_adj= $row['w_dRtg_adj'];
			//${$school}->opp_pace = $row['opp_pace'];
			//${$school}->opp_oRtg= $row['opp_oRtg'];
			//${$school}->opp_dRtg= $row['opp_dRtg'];
			
		}
		//${$school}->addScore($row);
		$teams[$school]=${$school};
	}
	
	// get averages
	//$sql = "SELECT AVG(pace) pace, AVG(oRtg) oRtg, AVG(dRtg) dRtg, AVG(oRtg_adj) oRtg_adj, AVG(dRtg_adj) dRtg_adj, AVG(opp_pace) opp_pace, AVG(opp_oRtg) opp_oRtg, AVG(opp_dRtg) opp_dRtg FROM ratings;";
	if (!$db->query($sql)) {
  		printf("Errorcode: %d\n", $db->errno);
	} else {
		$res = $db->query($sql);
		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$avgs = $row;
		}
	}
	
	

	$db->close();
?>
	
	<table id="ratings">
	<thead>
	<tr>
		<!--<th>#</th>-->
		<th>Tm</th>
		<th>Conf</th>
		<th>Pace</th>
		<th>ORtg</th>
		<th>DRtg</th>
		<th>ORtg Adj</th>
		<th>DRtg Adj</th>
		<th>Net Rtg Adj</th>
		<th>Wtd ORtg Adj</th>
		<th>Wtd DRtg Adj</th>
		<th>Net Wtd Rtg Adj</th>
		<th>Pyth</th>
	</tr>
	</thead>
	<tbody>
<?php
	$n = 1;
	foreach($teams as $k=>$team) {
		echo "<tr>";
		//echo "<td>" . $n . "</td>";
		echo "<td>" . $team->teamName . "</td>";
		echo "<td>" . $team->conf . "</td>";
		echo "<td class='text-center'>" . $team->num('pace',1) . "</td>";
		echo "<td class='text-center'>" . $team->num('oRtg',1) . "</td>";
		echo "<td class='text-center'>" . $team->num('dRtg',1) . "</td>";
		echo "<td class='text-center'>" . $team->num('oRtg_adj',1) . "</td>";
		echo "<td class='text-center'>" . $team->num('dRtg_adj',1) . "</td>";
		$net = $team->num('oRtg_adj',1);
		$net-=$team->num('dRtg_adj',1);
		echo "<td class='text-center'>" . $net . "</td>";
		
		echo "<td class='text-center'>" . $team->num('w_oRtg_adj',1) . "</td>";
		echo "<td class='text-center'>" . $team->num('w_dRtg_adj',1) . "</td>";
		$net = $team->num('w_oRtg_adj',1);
		$net-=$team->num('w_dRtg_adj',1);
		echo "<td class='text-center'>" . $net . "</td>";
		echo "<td class='text-center'>" . $team->pyth() . "</td>";

		echo "</tr>";
	$n++;
	}
	
?>
	</tbody>
	</table>
	<br/><br/>

<?php 
	echo "Ratings updated with games through " . date('Y-m-d', strtotime('-1 day')). ".";
?>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
	<script type="text/javascript">
		/*$(document).ready(function() {
			var avgs=<?php echo json_encode($avgs);?>;
			var teams=<?php echo json_encode($teams);?>;			
			console.log(avgs);
			console.log(model_game(teams[24],teams[167], avgs));
			
		}); */
	</script>
</html>