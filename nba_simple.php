<html>
<head>
<title>Sim</title>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');
	
	// DATABASE
	function getConnected() {
		$mysqli = new mysqli_connect('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_bb2014');
		if($mysqli->connect_error) {
			die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
		}
		return $mysqli;
	}
	
	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_bb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}

	//avgs
	$sql = "SELECT avg(pace) pace FROM stats_o;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$pace = $row['pace'];
	}
	
	//teams
	$sql = "SELECT t.id, t.team, 2P, 3P, FT, FTA, POSS, PACE, 2*(2P/POSS) 2Pr, 2*(3P/POSS) 3Pr FROM teams t INNER JOIN stats_o o ON t.id = o.team_id;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$teams[] = $row;
	}
	
	$db->close();

/*
RATES
SELECT team_id, (2P/POSS) 2Pr, (3P/POSS) 3Pr, ((FT/FTA)*(FTA/POSS)) FTr FROM stats_o

POSS
UPDATE  (stats_o o INNER JOIN stats_d d ON o.team_id = d.team_id)
SET o.POSS = 0.5 * ((o.2PA + o.3PA + 0.4 * o.FTA - 1.07*(o.ORB/(o.ORB + d.DRB)) * ((o.2PA+o.3PA)-(o.2P+o.3P)) + o.TOV) + (d.2PA + d.3PA + 0.4 * d.FTA - 1.07*(d.ORB/(d.ORB + o.DRB)) * ((d.2PA+d.3PA)-(d.2P+d.3P)) + d.TOV));

PACE
UPDATE (stats_o o INNER JOIN stats_d d ON o.team_id = d.team_id) SET o.PACE = 48 * ((o.POSS)/((o.MP/5)));
*/
?>
</head>
<body>
<div id='container'>
<div id='content'>
	<form>
		<select id="v">
			<option value></option>
			<?php foreach($teams as $k => $tm) {
				echo "<option value=\"" . $k . "\">" . $tm['team']. "</option>";	
			}?>
		</select>
		
		<select id="h">
			<option value></option>
			<?php foreach($teams as $k => $tm) {
				echo "<option value=\"" . $k . "\">" . $tm['team'] . "</option>";	
			}?>
		</select>
		<button type=button id="simNow">Sim</button>
	</form>


	<div id="sim"><div id="current"></div></div>
	

</div>
</div>
</body>

<script type="text/javascript">
	$(document).ready(function() {
		tms = <?php echo json_encode($teams);?>;
		avg_pace = <?php echo $pace;?>;
		console.log(tms);
		
		$('button#simNow').on('click', function() {
			var v=$('#v').val();
			var h=$('#h').val();
			sim(v,h);
		});
		
		function sim(v,h) {
			var matchup = tms[v].team + " @ " + tms[h].team; 
			var g_pace = Math.round((tms[v].PACE/avg_pace) * (tms[h].PACE/avg_pace) * avg_pace,0);

			var str = "<h2>" + matchup + "</h2>";
			str += "<p>Estimated Possessions: " + g_pace + "</p>";
			
			var game = new Object();
			var tally = new Object();
			tally.v_wins = 0;
			tally.v_pts = 0;
			tally.h_wins = 0;
			tally.h_pts = 0;
			tally.ties = 0;
			
			game.vpts = 0;
			game.v2pr = parseFloat(tms[v]['2Pr']);
			game.v3pr = parseFloat(game.v2pr) + parseFloat(tms[v]['3Pr']);
			game.hpts = 3;
			game.h2pr = parseFloat(tms[h]['2Pr']);
			game.h3pr = parseFloat(game.h2pr) + parseFloat(tms[h]['3Pr']);
			for ($sample = 0; $sample < 10000; $sample++) {
				game.vpts = 0;
				game.hpts = 3;
				
				for(i = 1; i<=g_pace; i++) {
					var res = Math.random();
					//str += "<p>Poss " + i + ": " + res + "</p>";
					if (i%2==0) {	/* even records = vis */
						if (res < game.v2pr) {
							game.vpts = game.vpts+ 2;
							tally.v_pts = tally.v_pts+ 2;
							//str += "<p>" + tms[v].team + " scores 2 points.</p>";
						} else if (res < game.v3pr) {
							game.vpts = game.vpts+ 3;
							tally.v_pts = tally.v_pts+ 3;
							//str += "<p>" + tms[v].team + " scores 3 points.</p>";
						}
					} else {	/* odd records = home */
						if (res < game.h2pr) {
							game.hpts = game.hpts+ 2;
							tally.h_pts = tally.h_pts+ 2;
							//str += "<p>" + tms[h].team + " scores 2 points.</p>";
						} else if (res < game.h3pr) {
							game.hpts = game.hpts+ 3;
							tally.h_pts = tally.h_pts+ 3;
							//str += "<p>" + tms[h].team + " scores 3 points.</p>";
						}
					}
				}
				if (game.vpts > game.hpts) {tally.v_wins++;}
				else if (game.vpts < game.hpts) {tally.h_wins++;}
				else {tally.ties++;}
				//console.log(game);
			}
			str+="<p>Record: " + tally.v_wins + " - " + tally.h_wins + " - " + tally.ties + "</p>";
			str+="<p>Avg Score: " + (tally.v_pts/10000) + " - " + (tally.h_pts/10000) + "</p>";
			$('#sim').append(str);
		}
	});

</script>
</html>