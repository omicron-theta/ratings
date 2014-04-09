<html>
<head>
<?php

	include('bootstrap/functions.php');
	include('bootstrap/classes/Ratings.php');

	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2'));    
?>
</head>
<body>
<div id='container'>
<div id='content'>
<?php 

	set_time_limit(0);
	
	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	$sql = "SELECT id FROM teams WHERE conf <> \"Non-Div I\";";
	if (!$db->query($sql)) {
  		printf("Errorcode: %d\n", $db->errno);
  		exit;
	} else {
		$res = $db->query($sql);
		$teams = array();
		while($row = $res->fetch_array(MYSQLI_ASSOC)) {
			$k = $row['id'];
			print_r($row);

		$sql = "SELECT t.id, t.team, IF(t.id = g.home_id, \"H\", \"V\") loc, opp.team opp, IF(t.conf=opp.conf,1,0) conf, g.MP, IF(t.id=g.home_id, g.hFG,g.vFG) FG, IF(t.id=g.home_id, g.hFGA,g.vFGA) FGA, IF(t.id=g.home_id, g.h3P,g.v3P) 3P, IF(t.id=g.home_id, g.h3PA,g.v3PA) 3PA, IF(t.id=g.home_id, g.hFT,g.vFT) FT, IF(t.id=g.home_id, g.hFTA,g.vFTA) FTA, IF(t.id=g.home_id, g.hORB,g.vORB) ORB, IF(t.id=g.home_id, g.hDRB,g.vDRB) DRB, IF(t.id=g.home_id, g.hAST,g.vAST) AST, IF(t.id=g.home_id, g.hST,g.vST) ST, IF(t.id=g.home_id, g.hBL,g.vBL) BL, IF(t.id=g.home_id, g.hTOV,g.vTOV) TOV, IF(t.id=g.home_id, g.hPF,g.vPF) PF, IF(t.id=g.home_id, g.hPTS,g.vPTS) PTS, IF(t.id=g.home_id,g.vFG,g.hFG) oppFG, IF(t.id=g.home_id,g.vFGA,g.hFGA) oppFGA, IF(t.id=g.home_id,g.v3P,g.h3P) opp3P, IF(t.id=g.home_id,g.v3PA,g.h3PA) opp3PA, IF(t.id=g.home_id,g.vFT,g.hFT) oppFT, IF(t.id=g.home_id,g.vFTA,g.hFTA) oppFTA, IF(t.id=g.home_id,g.vORB,g.hORB) oppORB, IF(t.id=g.home_id,g.vDRB,g.hDRB) oppDRB, IF(t.id=g.home_id,g.vAST,g.hAST) oppAST, IF(t.id=g.home_id,g.vST,g.hST) oppST, IF(t.id=g.home_id,g.vBL,g.hBL) oppBL, IF(t.id=g.home_id,g.vTOV,g.hTOV) oppTOV, IF(t.id=g.home_id,g.vPF,g.hPF) oppPF, IF(t.id=g.home_id,g.vPTS,g.hPTS) oppPTS, SUM(o_g.MP) opp_MP, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vFG,o_g.hFG)) oppOff_FG,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vFGA,o_g.hFGA)) oppOff_FGA,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.v3P,o_g.h3P)) oppOff_3P,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.v3PA,o_g.h3PA)) oppOff_3PA,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vFT,o_g.hFT)) oppOff_FT,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vFTA,o_g.hFTA)) oppOff_FTA,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vORB,o_g.hORB)) oppOff_ORB,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vDRB,o_g.hDRB)) oppOff_DRB,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vAST,o_g.hAST)) oppOff_AST,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vST,o_g.hST)) oppOff_ST,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vBL,o_g.hBL)) oppOff_BL,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vTOV,o_g.hTOV)) oppOff_TOV,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vPF,o_g.hPF)) oppOff_PF,  SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.vPTS,o_g.hPTS)) oppOff_PTS, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hFG,o_g.vFG)) oppDef_FG, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hFGA,o_g.vFGA)) oppDef_FGA, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.h3P,o_g.v3P)) oppDef_3P, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.h3PA,o_g.v3PA)) oppDef_3PA, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hFT,o_g.vFT)) oppDef_FT, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hFTA,o_g.vFTA)) oppDef_FTA, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hORB,o_g.vORB)) oppDef_ORB, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hDRB,o_g.vDRB)) oppDef_DRB, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hAST,o_g.vAST)) oppDef_AST, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hST,o_g.vST)) oppDef_ST, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hBL,o_g.vBL)) oppDef_BL, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hTOV,o_g.vTOV)) oppDef_TOV, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hPF,o_g.vPF)) oppDef_PF, SUM(IF(IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id,o_g.hPTS,o_g.vPTS)) oppDef_PTS  FROM teams t INNER JOIN games g ON (t.id = g.home_id OR t.id = g.visitor_id) AND t.conf <> \"Non-Div I\" AND t.id= ". $k . " INNER JOIN teams opp ON IF(t.id = g.home_id, g.visitor_id, g.home_id) = opp.id AND opp.conf <> \"Non-Div I\" INNER JOIN games o_g ON (IF(t.id=g.home_id,g.visitor_id,g.home_id)=o_g.visitor_id AND IF(t.id=g.home_id,g.home_id,g.visitor_id)<>o_g.home_id)     OR(IF(t.id=g.visitor_id,g.home_id,g.visitor_id)=o_g.home_id AND IF(t.id=g.visitor_id,g.visitor_id,g.home_id)<>o_g.visitor_id) GROUP BY t.id, t.team, IF(t.id = g.home_id, \"H\", \"V\"), opp.team, IF(t.conf=opp.conf,1,0), IF(t.id=g.home_id, g.hFG,g.vFG), IF(t.id=g.home_id, g.hFGA,g.vFGA), IF(t.id=g.home_id, g.h3P,g.v3P), IF(t.id=g.home_id, g.h3PA,g.v3PA), IF(t.id=g.home_id, g.hFT,g.vFT), IF(t.id=g.home_id, g.hFTA,g.vFTA), IF(t.id=g.home_id, g.hORB,g.vORB), IF(t.id=g.home_id, g.hDRB,g.vDRB), IF(t.id=g.home_id, g.hAST,g.vAST), IF(t.id=g.home_id, g.hST,g.vST), IF(t.id=g.home_id, g.hBL,g.vBL), IF(t.id=g.home_id, g.hTOV,g.vTOV), IF(t.id=g.home_id, g.hPF,g.vPF), IF(t.id=g.home_id, g.hPTS,g.vPTS), IF(t.id=g.home_id,g.vFG,g.hFG), IF(t.id=g.home_id,g.vFGA,g.hFGA), IF(t.id=g.home_id,g.v3P,g.h3P), IF(t.id=g.home_id,g.v3PA,g.h3PA), IF(t.id=g.home_id,g.vFT,g.hFT), IF(t.id=g.home_id,g.vFTA,g.hFTA), IF(t.id=g.home_id,g.vORB,g.hORB), IF(t.id=g.home_id,g.vDRB,g.hDRB), IF(t.id=g.home_id,g.vAST,g.hAST), IF(t.id=g.home_id,g.vST,g.hST), IF(t.id=g.home_id,g.vBL,g.hBL), IF(t.id=g.home_id,g.vTOV,g.hTOV), IF(t.id=g.home_id,g.vPF,g.hPF), IF(t.id=g.home_id,g.vPTS,g.hPTS) ORDER BY t.team, opp.team;";
		
			if (!$db->query($sql)) {
		  		printf("Errorcode: %d\n", $db->errno);
		  		exit;
			} else {
				$res2 = $db->query($sql);
				while($row2 = $res2->fetch_array(MYSQLI_ASSOC)) {
					$school = $row2['id'];
					if (!isset(${$school})) {
						${$school} = new Rating($row2['team']);
					}
					${$school}->addScore($row2);
					$teams[$school]=${$school};
				}
			}
			
	
		}
	}
	$db->close();
	
		
	foreach($teams as $team) {$team->calc_stats();}
	set_avgs($teams);
	foreach($teams as $team) {$team->calc_adj_stats();}

	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
	
	$i = 0;	
	foreach($teams as $k=>$team) {
		//echo "<br/>";
		//print_r($team);
		//echo "<br/>";
		
		$sql = "UPDATE ratings SET pyth=" . $team->pyth() . ", pace=" . $team->num('pace',4) . 
		", oRtg=" . $team->num('oRtg',4) . 
		", dRtg=" . $team->num('dRtg',4) . 
		", oRtg_adj=" . $team->num('oRtg_adj',4) . 
		", dRtg_adj=" . $team->num('dRtg_adj',4) .
		", opp_pace=" . $team->num('opp_pace',4) . 
		", opp_oRtg=" . $team->num('opp_oRtg',4) . 
		", opp_dRtg=" . $team->num('opp_dRtg',4) . 
		" WHERE team_id=" . $k . ";";
		if($db->query($sql) === TRUE) {
			echo "<p>" . $team->teamName . ": " . $sql . "</p>";
			$i++;
		} else {
			echo "<p>" . $team->teamName . " err: " . $sql . "</p>";
		}
	}
	$db->close();
	if ($i == 351) {echo "All records updated.";} else {$i . " records updated.";}
	
?>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
</html>