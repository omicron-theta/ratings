<html>
<head>
<title>Sim</title>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');
	echo css(array('styles'));
	
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
	//$sql = "SELECT avg(pace) pace FROM stats_o;";
	$sql = "SELECT AVG(o.PACE) pace, AVG(o.TOV/(o.2PA + o.3PA + 0.44*o.FTA + o.TOV)) TOVp, AVG(o.2PA/(o.2PA+o.3PA)) 2Pr, AVG(o.3PA/(o.2PA+o.3PA)) 3Pr, AVG(o.2P/o.2PA) 2Pp, AVG(o.3P/o.3PA) 3Pp, AVG(o.FTA/(o.2PA+o.3PA)) FTr, AVG(o.DRB/(d.ORB + o.DRB)) DRBp, AVG(d.ORB/(d.ORB + o.DRB)) DRBp2 FROM teams t INNER JOIN stats_o o ON t.id = o.team_id INNER JOIN stats_d d ON t.id = d.team_id;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$avgs= $row;
	}
	
	//teams		(ORB * (Tm MP / 5)) / (MP * (Tm ORB + Opp DRB))
	$sql = "SELECT t.id team_id, t.team, o.PACE, (o.TOV/(o.2PA + o.3PA + 0.44*o.FTA + o.TOV)) TOVp, (o.2PA/(o.2PA+o.3PA)) 2Pr, (o.3PA/(o.2PA+o.3PA)) 3Pr, (o.2P/o.2PA) 2Pp, (o.3P/o.3PA) 3Pp, (o.FTA/(o.2PA+o.3PA)) FTr, (o.FT/o.FTA) FTp, (o.ORB/(o.ORB + d.DRB)) ORBp FROM teams t INNER JOIN stats_o o ON t.id = o.team_id INNER JOIN stats_d d ON t.id = d.team_id;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$teams_off[] = $row;
	}
	
	$sql = "SELECT t.id team_id, t.team, (d.TOV/(d.2PA + d.3PA + 0.44*d.FTA + d.TOV)) TOVp, (d.2PA/(d.2PA+d.3PA)) 2Pr, (d.3PA/(d.2PA+d.3PA)) 3Pr, (d.2P/d.2PA) 2Pp, (d.3P/d.3PA) 3Pp, (d.FTA/(d.2PA+d.3PA)) FTr, (d.FT/d.FTA) FTp, (o.DRB/(o.DRB + d.ORB)) DRBp FROM teams t INNER JOIN stats_o o ON t.id = o.team_id INNER JOIN stats_d d ON t.id = d.team_id;";
	$res = $db->query($sql);
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$teams_def[] = $row;
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
			<?php foreach($teams_off as $k => $tm) {
				echo "<option value=\"" . $tm['team_id'] . "\">" . $tm['team']. "</option>";	
			}?>
		</select>
		
		<select id="h">
			<option value></option>
			<?php foreach($teams_off as $k => $tm) {
				echo "<option value=\"" . $tm['team_id'] . "\">" . $tm['team'] . "</option>";	
			}?>
		</select>
		<select id="favor">
			<option value="minus">-</option>
			<option value="plus">+</option>
		</select>
		<input id="spread" class="short" type="text">
		<button type=button id="simNow">Sim</button>
	</form>


	<div id="sim"><div id="current"></div></div>
	

</div>
</div>
</body>

<script type="text/javascript">
	$(document).ready(function() {
		tms = <?php echo json_encode($teams_off);?>;
		tms.unshift("Array of Teams");
		def = <?php echo json_encode($teams_def);?>;
		def.unshift("Array of Def");
		avg_pace = <?php echo $avgs['pace'];?>;
		avgs = <?php echo json_encode($avgs);?>;
		
		$('button#simNow').on('click', function() {
			var v=$('#v').val();
			var h=$('#h').val();
			var favor=$('#favor').val();
			var spread=$('#spread').val();
			
			run_sim(v,h, favor, spread);
		});

		function run_sim(v,h, favor, spread) {
			//$('#sim').append("<p>Running Simulation.</p>");
			$('#sim').append("<p>" + tms[v].team + " @ " + tms[h].team + " (" + favor + " " + spread + ")</p>");
	
			function Team (id) {
				this.team_id=id;
				this.name = tms[id].team;
				this.pace=tms[id].PACE;
				this.tov=tms[id].TOVp;
				this.rate2P=tms[id]['2Pr'];
				this.rate3P=tms[id]['3Pr'];
				this.perc2P=tms[id]['2Pp'];
				this.perc3P=tms[id]['3Pp'];
				this.ftr=tms[id].FTr;
				this.ftp=tms[id].FTp;
				this.orb=tms[id].ORBp;
				this.pts=0;
			}
			
			function Game(v,h) {
				this.h = new Team(h);
				this.v = new Team(v);

				this.h.perc2P=(this.h.perc2P * (def[this.v.team_id]['2Pp']/avgs['2Pp']));
				this.h.perc3P=(this.h.perc3P * (def[this.v.team_id]['3Pp']/avgs['3Pp']));
				this.v.perc2P=(this.v.perc2P * (def[this.h.team_id]['2Pp']/avgs['2Pp']));
				this.v.perc3P=(this.v.perc3P * (def[this.h.team_id]['3Pp']/avgs['3Pp']));
				
				this.h.pts = 0;
				this.v.pts = 0;
				this.possessions = 1;
				this.ball = (Math.random() < 0.5 ? 'h' : 'v');
				
				this.pace = Math.round((this.h.pace/avgs.pace) * (this.v.pace/avgs.pace) * avgs.pace,0);
				this.change_poss = change_poss;
				function change_poss() {
					if (this.ball=="h") {this.ball="v";} else {this.ball="h";}
				}
			}
			
			
			function Sim (v,h, favor, spread) {
				this.v = new Team(v)
				this.h = new Team(h)
				//avg points
				this.v.pts = 0;
				this.h.pts = 0;
				// straight up
				this.v.wins = 0;
				this.h.wins = 0;
				this.ties = 0;
				
				this.favor=favor;
				this.spread=spread;
				// ATS
				this.h.covers = 0;
				this.v.covers = 0;
				this.push = 0;
				
				this.pace = Math.round((this.h.pace/avg_pace) * (this.v.pace/avg_pace) * avg_pace,0);
				this.games = [];
				
				this.plus = function (a,b) { return a + b;}
				this.minus = function (a,b) { return a - b;}
				
			}
			
			var sim = new Sim(tms[v].team_id,tms[h].team_id, favor, spread);
			var str = "<h2>" + sim.v.name + " @ " + sim.h.name + " (Est. Poss: " + sim.pace + ")</h2>";
			sims_count = 10000;
			
			for(sims = 1; sims <=sims_count; sims++) {
				var game = new Game(tms[v].team_id,tms[h].team_id);
				
				//var str = "<h2>" + game.v.name + " @ " + game.h.name + " (Est. Poss: " + game.pace + ")</h2>";
				//str += "<p>Estimated Possessions: " + game.pace + "</p>";
				//console.log(game);
			
				var shot = 0;
				var shot_res = false;
				var res_msg = "";
				var shotclock = true;
				var orb = false;
				var foul = false;
				var foul_res = false;
				var ats_res = "";
				while (game.possessions <= (2*game.pace)) {
					shotclock = true; 	// start possession
					orb = false;
					//str+="<p>" + game.possessions + ": " + game[game.ball].name + "</p>";
					while (shotclock) {
						res_msg = "";
						if (Math.random() < game[game.ball].tov) {	// turnover
						   res_msg = "Turnover.";
						   shotclock = false;
						} else {	// shot result
						   shot=(Math.random() < game[game.ball].rate2P ? 2 : 3);
						   shot_res = (Math.random() < game[game.ball]["perc"+shot+"P"] ? true : false);
						   game[game.ball].pts += (shot_res ? parseInt(shot) : 0);
						   res_msg = (shot_res ? "Made " : "Missed ") + shot + "P.<br/>";
						   
						   // check whether fouled shooting
						   foul = (Math.random() < game[game.ball].ftr ? true : false);
						   foul_res = (foul && Math.random() < game[game.ball].ftp ? true : false);
						   game[game.ball].pts += (foul_res ? 1 : 0);
						   res_msg += (foul ? (foul_res ? "Made Free Throw." : "Missed Free Throw.") : "");
						   
						   if ((foul==true && foul_res==false) || (shot_res == false && foul == false)) {	// if foul or shot missed
						   	orb = (Math.random() < game[game.ball].orb ? true : false);
						   	res_msg += (orb && shot_res==false ? "Offensive Rebound." : "");
						   }
						   
						   shotclock = ((shot_res && foul==false) || foul_res ? false : orb);
						}
						//str += "<p>" + res_msg + "</p>";
					}
					//str += "<hr/>";
					game.change_poss();
					game.possessions++;
				}
				
				// add aggregate points
				sim.v.pts += game.v.pts;
				sim.h.pts += game.h.pts;
				
				//add wins
				if (Math.round(game.v.pts,0) > Math.round(game.h.pts,0)) {
					sim.v.wins++;
				} else if (Math.round(game.h.pts,0) > Math.round(game.v.pts,0)) {
					sim.h.wins++;
				} else {sim.ties++;}

				// add ATS stats
				if (Math.round(game.v.pts,0) > sim[sim.favor](Math.round(game.h.pts,0),parseFloat(sim.spread))) {
					sim.v.covers++;
					var ats_res = "V";
				} else if (Math.round(game.v.pts,0) < sim[sim.favor](Math.round(game.h.pts,0),parseFloat(sim.spread))) {
					sim.h.covers++;
					var ats_res = "H";
				} else {
					sim.push++;
					var ats_res = "P";
				}

				sim.games.push(game);
				
				//str="<p><b>Final</b><br/>";
				//str+=game.v.name + ": " + game.v.pts + "<br/>";
				//str+=game.h.name + ": " + game.h.pts + "<br/>";
				//str+=ats_res + "</p>";
				//$('#sim').append(str);
			}
			str="<p>Simulation Complete</p>";
			str+="<p>SU:</p>";
			str+="<p>" + sim.v.name + ": " + sim.v.wins + "-" + sim.h.wins + "-" + sim.ties + " (" + (sim.v.wins/(sims_count/100)) + "%)<br/>";		
			str+=sim.h.name + ": " + sim.h.wins + "-" + sim.v.wins + "-" + sim.ties + " (" + (sim.h.wins/(sims_count/100)) + "%)</p>";

			str+="<p>ATS:</p>";
			str+="<p>" + sim.v.name + ": " + sim.v.covers+ "-" + sim.h.covers + "-" + sim.push+ " (" + (sim.v.covers/(sims_count/100)) + "%)<br/>";		
			str+=sim.h.name + ": " + sim.h.covers + "-" + sim.v.covers + "-" + sim.push+ " (" + (sim.h.covers/(sims_count/100)) + "%)</p>";
			
			str+="<p>Average Score</p>";
			str+="<p>" + Math.round(sim.v.pts/sims_count,0) + "-" + Math.round(sim.h.pts/sims_count,0) + "</p>";
			
			$('#sim').append(str);
			
			console.log(sim);
		}
	});

</script>
</html>