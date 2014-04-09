<html>
<head>
<?php
	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2','tablesorter','tablesorter.widgets','ratings'));
?>
</head>
<body>
<div id='container'>
<div id='content'>
<?php 

$run = false;
if ($run) :
	//$date = new DateTime('2014-4-7');
	//$date = new DateTime(date('Y-m-d', strtotime('-1 day')));
	set_time_limit(0);

	//$handle = fopen('games.csv', 'a+');
	//fwrite($handle, "date,link,t1,t1h1,t1h2,t1ot,t1f,t2,t2h1,t2h2,t2ot,t2f\r\n");
	//fwrite($handle, "date,link,visitor,v1H,v2H,vOT,vT,home,h1H,h2H,hOT,hT\r\n");
	$month = date_format($date, 'n');
	$day = date_format($date, 'j');
	$year = date_format($date, 'Y');
			
	//while ($month > 1 || $day <= 27) {
	// READ AND SAVE HTML OF DAILY BOX SCORE PAGES
		$month = date_format($date, 'n');
		$day = date_format($date, 'j');
		$year = date_format($date, 'Y');
		$url = 'http://www.sports-reference.com/cbb/boxscores/index.cgi?month=' . $month . '&day=' . $day . '&year=' . $year;
		//echo '<p>' . $url . '</p>';
		$dt = date_format($date, 'Y') . '-' . date_format($date, 'm') . '-' . date_format($date, 'd');
		$fname = 'daily/' . date_format($date, 'm') . date_format($date, 'd') . date_format($date, 'Y') . '.txt';
		echo "<p>" . $fname . "</p>";
		if (!$src = file_get_contents($url)) {
			echo 'Error: ' . $fname . ' - ' . E_WARNING;
			break;
		}
		file_put_contents($fname, $src);
		
	// OPEN TXT FILES FOR DAILY BOX SCORE PAGES AND PARSE DATA INTO ARRAY
		$contents = file_get_contents($fname);
		$strMatch = '"<tr>\s*<td>(?:\([0-9]*\)\s*)*(?:<a href=\"/cbb/schools/.*/2014.html\">)*(.*)(?:</a>)*</td>\s*(:?<td class=\"align_right\">(.*)</td>\s*){3,}\s*</tr>\s*<tr>\s*<td><a href=\"/cbb/schools/.*/2014.html\">(.*)</a></td>\s*(:?<td class=\"align_right\">(.*)</td>\s*){3,}\s*</tr>"'; 		// includes ranked and OT
		
		$strMatch = '"<tr>\s*<td>(?:\([0-9]*\)\s*)*(?:<a href=\"/cbb/schools/.*/2014.html\">)*(.*)(?:</a>)*</td>\s*(:?<td class=\"align_right\">(.*)</td>\s*){3,}\s*</tr>\s*<tr>\s*<td><a href=\"/cbb/schools/(?<home_link_name>.*)/2014.html\">(.*)</a></td>\s*(:?<td class=\"align_right\">(.*)</td>\s*){3,}\s*</tr>"'; 		// includes ranked and OT and boxscore link
		
		
		$matches = array();
		preg_match_all($strMatch, $contents, $matches);
		//print_r($matches);
		
		$gms = $matches[0];
		$games = array();
		foreach($gms as $i => $gm) {
			$gm = trim(strip_tags($gms[$i]));
			$regex = '%(?<=\\d)(?=\\D)|(?=\\d)(?<=\\D)%';
			$game = preg_split($regex, $gm);
			
			$j = 0;
			while ($j < count($game)) {
				if(strlen(trim($game[$j])) == 0) {array_splice($game,$j,1);}
				else {$j++;}
			}
			
			$len = count($game);
			$dt = $year . '-' . $date->format('m') . '-' . $date->format('d');
			$games[$i]['home_link_name'] = 'http://www.sports-reference.com/cbb/boxscores/' . $dt . '-' . $matches['home_link_name'][$i] . '.html';
			$games[$i]['visitor'] = trim($game[0]);
			$games[$i]['v1H'] = $game[1];
			$games[$i]['v2H'] = $game[2];
			
			$vt = ($len/2)-1;
			$vOT = 0;
			for($k = 3 ; $k < $vt ; $k++) {	// add up totals from all OT
				$vOT += $game[$k];
			}
			$games[$i]['vOT'] = $vOT;
			$games[$i]['vT'] = $game[$vt];
			
			$h = $vt+1;
			$games[$i]['home'] = trim($game[$h]);
			$games[$i]['h1H'] = $game[$h+1];
			$games[$i]['h2H'] = $game[$h+2];
			$hOT = 0;
			for($k = $h+3 ; $k < ($len-1) ; $k++) {	// add up totals from all OT
				$hOT += $game[$k];
			}
			$games[$i]['hOT'] = $hOT;
			$games[$i]['hT'] = end($game);
		}

		
		$regex = '#<td align="left" >School Totals</td>
   <td align="right" >(?<MP>.*)</td>
   <td align="right" >(?<FG>.*)</td>
   <td align="right" >(?<FGA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >.*</td>
   <td align="right" >.*</td>
   <td align="right" >.*</td>
   <td align="right" >(?<3P>.*)</td>
   <td align="right" >(?<3PA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<FT>.*)</td>
   <td align="right" >(?<FTA>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<ORB>.*)</td>
   <td align="right" >(?<DRB>.*)</td>
   <td align="right" >.*</td>
   <td align="right" >(?<AST>.*)</td>
   <td align="right" >(?<ST>.*)</td>
   <td align="right" >(?<BL>.*)</td>
   <td align="right" >(?<TOV>.*)</td>
   <td align="right" >(?<PF>.*)</td>
   <td align="right" >(?<PTS>.*)</td>
</tr>#';

		$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
		if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
		
		foreach($games as $k => &$game) {
			$url = $game['home_link_name'];
			$fname = str_replace('http://www.sports-reference.com/cbb/boxscores/', 'box_scores/', $url);
			$fname = str_replace('.html', '.txt', $fname);
			if (!$src = file_get_contents($url)) {
				echo 'Error: ' . $fname . ' - ' . E_WARNING;
				break;
			}
			file_put_contents($fname, $src);
			
			$contents = file_get_contents($fname);
			$matches = array();
			preg_match_all($regex, $contents, $matches);
			
			foreach($matches as $st => $stats) {
				if (is_string($st)) {
					$game['v' . $st] = $stats[0];
					$game['h' . $st] = $stats[1];
				}
			}
			sleep(5);
$sql = "INSERT INTO  games VALUES (NULL, \"" . $dt . "\", (SELECT id FROM teams WHERE team=\"" . htmlspecialchars_decode($game['home']) . "\"), (SELECT id FROM teams WHERE team=\"" . htmlspecialchars_decode($game['visitor']) . "\"), " . $game['vMP'] . ", " . $game['vFG'] . ", " . $game['vFGA'] . ", " . $game['v3P'] . ", " . $game['v3PA'] . ", " . $game['vFT'] . ", " . $game['vFTA'] . ", " . $game['vORB'] . ", " . $game['vDRB'] . ", " . $game['vAST'] . ", " . $game['vST'] . ", " . $game['vBL'] . ", " . $game['vTOV'] . ", " . $game['vPF'] . ", " . $game['vPTS'] . ", " . $game['hFG'] . ", " . $game['hFGA'] . ", " . $game['h3P'] . ", " . $game['h3PA'] . ", " . $game['hFT'] . ", " . $game['hFTA'] . ", " . $game['hORB'] . ", " . $game['hDRB'] . ", " . $game['hAST'] . ", " . $game['hST'] . ", " . $game['hBL'] . ", " . $game['hTOV'] . ", " . $game['hPF'] . ", " . $game['hPTS'] . ");";
			
			
			if($db->query($sql) === TRUE) {
				echo '<p>UPDATED: ' . $sql . '</p>';
			} else {
				echo '<p>' . date('m/d/Y') . ' - ERROR: ' . $sql . '</p>';
				$handle = fopen('import_errors.txt', 'a+');
				fwrite($handle, $sql . "\r\n");
				fclose($handle);
			}
			
		}
		$db->close();
		
		$date->modify('+1 day');
	//}

?>
<b>Done.</b>
<?php
	else:
		echo "<b>Disabled</b>";
	endif;
?>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
</html>