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
<?php 
	$team_id = $_GET['t'];

	set_time_limit(0);
	$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
	if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}

	// get list of all games (arrTeams[t_id][arrGames])
	$sql = 'SELECT t.id, t.team, g.date, g.MP, 100*(DATEDIFF(g.date,"2013-11-7") / DATEDIFF("2014-3-10","2013-11-7")) progress,
	IF(t.id = g.home_id, hPTS, vPTS) pts, 
	IF(t.id = g.home_id, vPTS, hPTS) d_pts, 
	(0.5 * (hFGA + (0.475 * hFTA) - hORB + hTOV) + 0.5 * (vFGA + (0.475 * vFTA) - vORB + vTOV)) possessions 
	FROM teams t INNER JOIN games g ON t.id = g.home_id OR t.id = g.visitor_id 
	WHERE t.id = ' . $team_id . ' ORDER BY g.date;';

	$res = $db->query($sql);
	
	$stats = array (
		'min' => 0,
		'poss' => 0,
		'pts' => 0,
		'd_pts' => 0
	);
	
	while($row = $res->fetch_array(MYSQLI_ASSOC)) {
		$teamName = $row['team'];
		$stats['min'] += $row['MP'];
		$stats['poss'] += $row['possessions'];
		$stats['pts'] += $row['pts'];
		$stats['d_pts'] += $row['d_pts'];

		$cumm[$row['progress']] = array (
			'oRtg' => 100*($stats['pts']/$stats['poss']),
			'dRtg' => 100*($stats['d_pts']/$stats['poss'])
		);

	}
	foreach($cumm as $prog => $rtgs) {
		$graph_lbls[] = $prog;
		$graph_oRtg[] = $rtgs['oRtg'];
		$graph_dRtg[] = $rtgs['dRtg'];
		$graph_avg[] = 102;
	}

	$graph_lbls = json_encode($graph_lbls);
	$graph_oRtg = json_encode($graph_oRtg);
	$graph_dRtg = json_encode($graph_dRtg);
	$graph_avg = json_encode($graph_avg);

	$db->close();
	
	echo '<canvas id="tm_' . $team_id . '" height="200" width="368"></canvas>';
?>
<script type='text/javascript'>
	 //window.onload = function() {
	drawGraph = function() {
		var line = new RGraph.Line('tm_<?php echo $team_id;?>', <?php echo $graph_oRtg. ',' .  $graph_dRtg . ',' .  $graph_avg;?>);
			line.Set('title', '<?php echo $teamName;?>');
			line.Set('title.color', '#fff');
			line.Set('spline', true);
			line.Set('key', ['O-Rtg','D-Rtg', 'Avg']);
			line.Set('key.position.x', 300);
			line.Set('key.position.y', 130);
			line.Set('text.size', 10);
			line.Set('tickmarks.linewidth', 0);
			line.Set('numxticks', 0);
			line.Set('numyticks', 0);
			line.Set('ymax', 130);
			line.Set('ymin', 60);
			line.Set('background.grid', false);
			line.Set('colors', ['red', 'blue', 'grey']);
			line.Set('linewidth', 1);
			line.Set('gutter.left', 0);
			line.Set('gutter.right', 0);
			line.Set('shadow',true);
			line.Set('shadow.color','#aaa');
			//line.Set('shadow.blur',1);
			line.Draw();
	};
</script>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
</html>