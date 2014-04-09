<html>
<head>
</head>
<body>
<div id='container'>
<div id='content'>
<?php 

	//$date = new DateTime(date('Y-m-d', strtotime('-1 day')));
	$date = new DateTime(date('Y-m-d'));
	set_time_limit(0);

	//$handle = fopen('games.csv', 'a+');
	//fwrite($handle, "date,link,t1,t1h1,t1h2,t1ot,t1f,t2,t2h1,t2h2,t2ot,t2f\r\n");
	//fwrite($handle, "date,link,visitor,v1H,v2H,vOT,vT,home,h1H,h2H,hOT,hT\r\n");
	$month = date_format($date, 'm');
	$day = date_format($date, 'd');
	$year = date_format($date, 'Y');
	
	// GET STATS FROM LEAGUE SUMMARY PAGE
	$url = 'http://www.basketball-reference.com/leagues/NBA_2014.html';
	$fname = 'stats/nba_stats' . $year . $month . $day . '.txt';
	
	if (!file_exists($fname)) {
		if (!$src = file_get_contents($url)) {
			echo 'Error: ' . $fname . ' - ' . E_WARNING;
			break;
		}
		file_put_contents($fname, $src);
		echo "<p>Source Code for " . $date->format('Y-m-d') . " saved.</p>";
	} else {
		echo "<p>File  '" . $date->format('Y-m-d') . "' already exists.</p>";
	}
	
	
		
	// OPEN TXT FILES FOR DAILY BOX SCORE PAGES AND PARSE DATA INTO ARRAY
	if (!$contents = file_get_contents($fname)) {
		echo "<p>Could not open " . $fname . ".</p>";	
		exit();
	}
		
	$regex = '%<td align="right"  csk=".*">.*</td>
   <td align="left" ><a href="/teams/.*/2014.html">(?<team>.*)</a></td>
   <td align="right" >(?<G>.*)</td>
   <td align="right" >(?<MP>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >.*</td>
   <td align="right" >.*</td>
   <td align="right" >(?<3P>.*)</td>
   <td align="right" >(?<3PA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<2P>.*)</td>
   <td align="right" >(?<2PA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<FT>.*)</td>
   <td align="right" >(?<FTA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<ORB>.*)</td>
   <td align="right" >(?<DRB>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<AST>.*)</td>
   <td align="right" >(?<STL>.*)</td>
   <td align="right" >(?<BLK>.*)</td>
   <td align="right" >(?<TOV>.*)</td>
   <td align="right" >(?<PF>.*)</td>
   <td align="right" >(?<PTS>.*)</td>
   <td align="right" >.*</td>%';

		
	$matches = array();
	preg_match_all($regex, $contents, $matches);
	//print_r($matches);
	$len = count($matches[0]);

	$sqls = array();
	for($k = 0 ; $k < $len ; $k++) {
		$tbl = ($k < 30 ? "stats_o" : "stats_d");
		$sqls[] = "UPDATE " . $tbl . " SET G= " . $matches['G'][$k] . ", MP= " . $matches['MP'][$k] . ", 3P= " . $matches['3P'][$k] . ", 3PA= " . $matches['3PA'][$k] . ", 2P= " . $matches['2P'][$k] . ", 2PA= " . $matches['2PA'][$k] . ", FT= " . $matches['FT'][$k] . ", FTA= " . $matches['FTA'][$k] . ", ORB= " . $matches['ORB'][$k] . ", DRB= " . $matches['DRB'][$k] . ", AST= " . $matches['AST'][$k] . ", STL= " . $matches['STL'][$k] . ", BLK= " . $matches['BLK'][$k] . ", TOV= " . $matches['TOV'][$k] . ", PF= " . $matches['PF'][$k] . ", PTS= " . $matches['PTS'][$k] . " WHERE team_id = (SELECT id FROM teams WHERE team='" . $matches['team'][$k] . "');";
		
	}

	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_bb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}

	foreach($sqls as $i => $sql) {
		if($db->query($sql) === TRUE) {
			echo "<p>" . $sql . "</p>";
			$i++;
		} else {
			echo "<p>ERROR: " . $sql . "</p>";
		}
	}
	
	$sql = "UPDATE  (stats_o o INNER JOIN stats_d d ON o.team_id = d.team_id)
SET o.POSS = 0.5 * ((o.2PA + o.3PA + 0.4 * o.FTA - 1.07*(o.ORB/(o.ORB + d.DRB)) * ((o.2PA+o.3PA)-(o.2P+o.3P)) + o.TOV) + (d.2PA + d.3PA + 0.4 * d.FTA - 1.07*(d.ORB/(d.ORB + o.DRB)) * ((d.2PA+d.3PA)-(d.2P+d.3P)) + d.TOV));";
	$db->query($sql);
	$sql = "UPDATE (stats_o o INNER JOIN stats_d d ON o.team_id = d.team_id) SET o.PACE = 48 * ((o.POSS)/((o.MP/5)));";
	$db->query($sql);
	echo "<p>Possessions and Pace Updated.</p>";

	$db->close();			
?>
<b>Done.</b>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
</html>