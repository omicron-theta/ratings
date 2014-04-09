<html>
<head>
<title>Omicron Ratings</title>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');

	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2', 'tablesorter','ratings'));    

	// get team ratings and d1 avgs
	set_time_limit(0);
	$sql = "SELECT * FROM teams INNER JOIN ratings ON teams.id = ratings.team_id ORDER BY team ASC;";
	
	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$school = $row['id'];
		if (!isset(${$school})) {
			${$school} = new Rating($row['team']);
			${$school}->pace = $row['pace'];
			${$school}->oRtg= $row['oRtg'];
			${$school}->dRtg= $row['dRtg'];
			${$school}->oRtg_adj= $row['oRtg_adj'];
			${$school}->dRtg_adj= $row['dRtg_adj'];
			${$school}->opp_pace = $row['opp_pace'];
			${$school}->opp_oRtg= $row['opp_oRtg'];
			${$school}->opp_dRtg= $row['opp_dRtg'];
			
		}
		//${$school}->addScore($row);
		$teams[$school]=${$school};
	}
	
	// get averages
	$sql = "SELECT AVG(pace) pace, AVG(oRtg) oRtg, AVG(dRtg) dRtg, AVG(oRtg_adj) oRtg_adj, AVG(dRtg_adj) dRtg_adj, AVG(opp_pace) opp_pace, AVG(opp_oRtg) opp_oRtg, AVG(opp_dRtg) opp_dRtg FROM ratings;";
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

	<script type="text/javascript">
		$(document).ready(function() {
			var avgs=<?php echo json_encode($avgs);?>;
			var teams=<?php echo json_encode($teams);?>;			
			//console.log(avgs);
			//console.log(model_game(teams[24],teams[167], avgs));
			
			$("#add_row").on("click", function() {
				add_row();
			});
		});
	</script>
</head>
<body>
<div id='container'>
<div id='content'>
<button id="add_row">Add Row</button>

	<table>
	<tbody>
	<tr>
		<td>V</td>
		<td>H</td>
		<td>Poss</td>
		<td>V_Pts</td>
		<td>H_Pts</td>
		<td>H_Line</td>
		<td>Total</td>
	</tr>
	<tr id="row1">
		<td>
			<select id="visitor1" onchange="ajaxUpdateModel();">
				<option value></option>
				<?php foreach($teams as $k => $team) {
					echo "<option value=\"" . $k . "\">" . $team->teamName . "</option>";	
				}?>
			</select>
		</td>
		<td>
			<select id="home1">
				<option value></option>
				<?php foreach($teams as $k => $team) {
					echo "<option value=\"" . $k . "\">" . $team->teamName . "</option>";	
				}?>
			</select>
		</td>
		<td class='text-center' id='poss1'></td>
		<td class='text-center' id='v_pts1'></td>
		<td class='text-center' id='h_pts1'></td>
		<td class='text-center' id='h_line1'></td>
		<td class='text-center' id='total'></td>
	</tr>
	</tbody>
	</table>	
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>

</html>