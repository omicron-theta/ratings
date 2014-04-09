<html>
<head>
<?php 
	include('bootstrap/functions.php');
?>
</head>
<body>
<div id='container'>
<div id='content'>
<?php 

/*
// get links for each school
$fname = 'ratings.txt';
$contents = file_get_contents($fname);
//$regex = '#<td align="left" ><a href="/cbb/schools/(?<linkname>.*)/2014.html">(?<school>.*)</a></td>#';
*/
//$regex = '#<td align="left" ><a href="/cbb/schools/(?<linkname>.*)/2014.html">(?<school>.*)</a></td>\s*<td align="left"  csk=".*"><a href="/cbb/conferences/.*/2014.html">(?<conf>.*)</a>#';
/*
$matches = array();
preg_match_all($regex, $contents, $matches);

$url = 'http://www.sports-reference.com/cbb/schools/school_link/2014.html';
function update_school($link) {
	global $url;
	$url = 'http://www.sports-reference.com/cbb/schools/' . $link . '/2014.html';
}
foreach($matches['linkname'] as $k => $link) {
	update_school($link);
	//echo '<p>' . $url . '</p>';
	//echo $matches['school'][$k] . ',' . $matches['conf'][$k] . ',' . $link . '<br />';
	//echo '<h2>' . $st . '</h2>';
	//print_r($val);
}

echo '<hr />';
*/

$db = new mysqli('localhost', 'rwcity_cbb', 'cbb2014', 'rwcity_cbb2014');
if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error());exit();}
$sql = "SELECT * FROM teams LIMIT 2,1;";
$res = $db->query($sql);
$teams = array();
while($row = $res->fetch_array(MYSQLI_ASSOC)) {$teams[] = $row;}

//$date = new DateTime('2014-15-1');
//$month = $date->format('n');
//$day = $date-format('j');
//$year = $date->format('Y');
//$dt = $date->format('m') . $date->format('d') . $date->format('Y') . '.txt';
$dt = '01162014.txt';

foreach($teams as $k => $tm) {
	$url = 'http://www.sports-reference.com/cbb/schools/' . $tm['srLinkName'] . '/2014.html';
	$fname = 'stats/' . $tm['srLinkName'] . '_' . $dt;
	//if (!$src = file_get_contents($url)) {
	//	echo 'Error: ' . $fname . ' - ' . E_WARNING;
	//	break;
	//}
	//file_put_contents($fname, $src);
	//echo '<p>' . $url . '</p>';
	echo '<p>' . $fname. '</p>';
	
	// get stats for each school
//	$fname = 'stats/arizona.txt';
	$contents = file_get_contents($fname);
	$regex = '#<tr class="">\r\n  <th data-stat="player" align="left"  class=" sort_default_asc" ></th>\r\n  <th data-stat="g" align="right"  class=""  tip="Games">G</th>\r\n  <th data-stat="mp" align="right"  class=""  tip="Minutes Played">MP</th>\r\n  <th data-stat="fg" align="right"  class=""  tip="Field Goals">FG</th>\r\n  <th data-stat="fga" align="right"  class=""  tip="Field Goal Attempts">FGA</th>\r\n  <th data-stat="fg_pct" align="right"  class=""  tip="Field Goal Percentage">FG%</th>\r\n  <th data-stat="fg2" align="right"  class=""  tip="2-Point Field Goals">2P</th>\r\n  <th data-stat="fg2a" align="right"  class=""  tip="2-Point Field Goal Attempts">2PA</th>\r\n  <th data-stat="fg2_pct" align="right"  class=""  tip="2-Point Field Goal Percentage">2P%</th>\r\n  <th data-stat="fg3" align="right"  class=""  tip="3-Point Field Goals">3P</th>\r\n  <th data-stat="fg3a" align="right"  class=""  tip="3-Point Field Goal Attempts">3PA</th>\r\n  <th data-stat="fg3_pct" align="right"  class=""  tip="3-Point Field Goal Percentage">3P%</th>\r\n  <th data-stat="ft" align="right"  class=""  tip="Free Throws">FT</th>\r\n  <th data-stat="fta" align="right"  class=""  tip="Free Throw Attempts">FTA</th>\r\n  <th data-stat="ft_pct" align="right"  class=""  tip="Free Throw Percentage">FT%</th>\r\n  <th data-stat="orb" align="right"  class=""  tip="Offensive Rebounds">ORB</th>\r\n  <th data-stat="drb" align="right"  class=""  tip="Defensive Rebounds">DRB</th>\r\n  <th data-stat="trb" align="right"  class=""  tip="Total Rebounds">TRB</th>\r\n  <th data-stat="ast" align="right"  class=""  tip="Assists">AST</th>\r\n  <th data-stat="stl" align="right"  class=""  tip="Steals">STL</th>\r\n  <th data-stat="blk" align="right"  class=""  tip="Blocks">BLK</th>\r\n  <th data-stat="tov" align="right"  class=""  tip="Turnovers">TOV</th>\r\n  <th data-stat="pf" align="right"  class=""  tip="Personal Fouls">PF</th>\r\n  <th data-stat="pts" align="right"  class=""  tip="Points">PTS</th>\r\n  <th data-stat="pts_per_g" align="right"  class=""  tip="Points Per Game">PTS/G</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr  class="">\r\n   <td align="left" >Team</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<o_fg>.*)</td>\r\n   <td align="right" >(?<o_fga>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<o_3p>.*)</td>\r\n   <td align="right" >(?<o_3pa>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<o_ft>.*)</td>\r\n   <td align="right" >(?<o_fta>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<o_orb>.*)</td>\r\n   <td align="right" >(?<o_drb>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<o_assists>.*)</td>\r\n   <td align="right" >(?<o_stls>.*)</td>\r\n   <td align="right" >(?<o_bl>.*)</td>\r\n   <td align="right" >(?<o_tov>.*)</td>\r\n   <td align="right" >(?<o_pf>.*)</td>\r\n   <td align="right" >(?<o_pts>.*)</td>\r\n   <td align="right" >.*</td>\r\n</tr>\r\n<tr  class="light_text">\r\n   <td align="left" >Rank</td>\r\n   <td align="right" ></td>\r\n   <td align="right" ></td>\r\n   <td align="right" >56th</td>\r\n   <td align="right" >148th</td>\r\n   <td align="right" >20th</td>\r\n   <td align="right" >28th</td>\r\n   <td align="right" >69th</td>\r\n   <td align="right" >47th</td>\r\n   <td align="right" >217th</td>\r\n   <td align="right" >281st</td>\r\n   <td align="right" >78th</td>\r\n   <td align="right" >105th</td>\r\n   <td align="right" >89th</td>\r\n   <td align="right" >232nd</td>\r\n   <td align="right" >44th</td>\r\n   <td align="right" >42nd</td>\r\n   <td align="right" >24th</td>\r\n   <td align="right" >22nd</td>\r\n   <td align="right" >168th</td>\r\n   <td align="right" >60th</td>\r\n   <td align="right" >125th</td>\r\n   <td align="right" >296th</td>\r\n   <td align="right" >78th</td>\r\n   <td align="right" >101st</td>\r\n</tr>\r\n<tr  class="">\r\n   <td align="left" >Opponent</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<d_fg>.*)</td>\r\n   <td align="right" >(?<d_fga>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<d_3p>.*)</td>\r\n   <td align="right" >(?<d_3pa>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<d_ft>.*)</td>\r\n   <td align="right" >(?<d_fta>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<d_orb>.*)</td>\r\n   <td align="right" >(?<d_drb>.*)</td>\r\n   <td align="right" >.*</td>\r\n   <td align="right" >(?<d_assists>.*)</td>\r\n   <td align="right" >(?<d_stls>.*)</td>\r\n   <td align="right" >(?<d_bl>.*)</td>\r\n   <td align="right" >(?<d_tov>.*)</td>\r\n   <td align="right" >(?<d_pf>.*)</td>\r\n   <td align="right" >(?<d_pts>.*)</td>\r\n   <td align="right" >.*</td>\r\n</tr>#';


$regex='#<tr\s*class="">\r\n\s*<th\s*data-stat="player"\s*align="left"\s*class="\s*sort_default_asc"\s*></th>\r\n\s*<th\s*data-stat="g"\s*align="right"\s*class=""\s*tip="Games">G</th>\r\n\s*<th\s*data-stat="mp"\s*align="right"\s*class=""\s*tip="Minutes\s*Played">MP</th>\r\n\s*<th\s*data-stat="fg"\s*align="right"\s*class=""\s*tip="Field\s*Goals">FG</th>\r\n\s*<th\s*data-stat="fga"\s*align="right"\s*class=""\s*tip="Field\s*Goal\s*Attempts">FGA</th>\r\n\s*<th\s*data-stat="fg_pct"\s*align="right"\s*class=""\s*tip="Field\s*Goal\s*Percentage">FG%</th>\r\n\s*<th\s*data-stat="fg2"\s*align="right"\s*class=""\s*tip="2-Point\s*Field\s*Goals">2P</th>\r\n\s*<th\s*data-stat="fg2a"\s*align="right"\s*class=""\s*tip="2-Point\s*Field\s*Goal\s*Attempts">2PA</th>\r\n\s*<th\s*data-stat="fg2_pct"\s*align="right"\s*class=""\s*tip="2-Point\s*Field\s*Goal\s*Percentage">2P%</th>\r\n\s*<th\s*data-stat="fg3"\s*align="right"\s*class=""\s*tip="3-Point\s*Field\s*Goals">3P</th>\r\n\s*<th\s*data-stat="fg3a"\s*align="right"\s*class=""\s*tip="3-Point\s*Field\s*Goal\s*Attempts">3PA</th>\r\n\s*<th\s*data-stat="fg3_pct"\s*align="right"\s*class=""\s*tip="3-Point\s*Field\s*Goal\s*Percentage">3P%</th>\r\n\s*<th\s*data-stat="ft"\s*align="right"\s*class=""\s*tip="Free\s*Throws">FT</th>\r\n\s*<th\s*data-stat="fta"\s*align="right"\s*class=""\s*tip="Free\s*Throw\s*Attempts">FTA</th>\r\n\s*<th\s*data-stat="ft_pct"\s*align="right"\s*class=""\s*tip="Free\s*Throw\s*Percentage">FT%</th>\r\n\s*<th\s*data-stat="orb"\s*align="right"\s*class=""\s*tip="Offensive\s*Rebounds">ORB</th>\r\n\s*<th\s*data-stat="drb"\s*align="right"\s*class=""\s*tip="Defensive\s*Rebounds">DRB</th>\r\n\s*<th\s*data-stat="trb"\s*align="right"\s*class=""\s*tip="Total\s*Rebounds">TRB</th>\r\n\s*<th\s*data-stat="ast"\s*align="right"\s*class=""\s*tip="Assists">AST</th>\r\n\s*<th\s*data-stat="stl"\s*align="right"\s*class=""\s*tip="Steals">STL</th>\r\n\s*<th\s*data-stat="blk"\s*align="right"\s*class=""\s*tip="Blocks">BLK</th>\r\n\s*<th\s*data-stat="tov"\s*align="right"\s*class=""\s*tip="Turnovers">TOV</th>\r\n\s*<th\s*data-stat="pf"\s*align="right"\s*class=""\s*tip="Personal\s*Fouls">PF</th>\r\n\s*<th\s*data-stat="pts"\s*align="right"\s*class=""\s*tip="Points">PTS</th>\r\n\s*<th\s*data-stat="pts_per_g"\s*align="right"\s*class=""\s*tip="Points\s*Per\s*Game">PTS/G</th>\r\n</tr>\r\n</thead>\r\n<tbody>\r\n<tr\s*class="">\r\n\s*<td\s*align="left"\s*>Team</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<o_fg>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_fga>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<o_3p>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_3pa>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<o_ft>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_fta>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<o_orb>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_drb>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<o_assists>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_stls>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_bl>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_tov>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_pf>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<o_pts>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n</tr>\r\n<tr\s*class="light_text">\r\n\s*<td\s*align="left"\s*>Rank</td>\r\n\s*<td\s*align="right"\s*></td>\r\n\s*<td\s*align="right"\s*></td>\r\n\s*<td\s*align="right"\s*>17th</td>\r\n\s*<td\s*align="right"\s*>59th</td>\r\n\s*<td\s*align="right"\s*>28th</td>\r\n\s*<td\s*align="right"\s*>63rd</td>\r\n\s*<td\s*align="right"\s*>202nd</td>\r\n\s*<td\s*align="right"\s*>3rd</td>\r\n\s*<td\s*align="right"\s*>25th</td>\r\n\s*<td\s*align="right"\s*>14th</td>\r\n\s*<td\s*align="right"\s*>165th</td>\r\n\s*<td\s*align="right"\s*>124th</td>\r\n\s*<td\s*align="right"\s*>131st</td>\r\n\s*<td\s*align="right"\s*>160th</td>\r\n\s*<td\s*align="right"\s*>262nd</td>\r\n\s*<td\s*align="right"\s*>10th</td>\r\n\s*<td\s*align="right"\s*>55th</td>\r\n\s*<td\s*align="right"\s*>6th</td>\r\n\s*<td\s*align="right"\s*>162nd</td>\r\n\s*<td\s*align="right"\s*>264th</td>\r\n\s*<td\s*align="right"\s*>17th</td>\r\n\s*<td\s*align="right"\s*>315th</td>\r\n\s*<td\s*align="right"\s*>21st</td>\r\n\s*<td\s*align="right"\s*>8th</td>\r\n</tr>\r\n<tr\s*class="">\r\n\s*<td\s*align="left"\s*>Opponent</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<d_fg>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_fga>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<d_3p>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_3pa>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<d_ft>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_fta>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<d_orb>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_drb>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n\s*<td\s*align="right"\s*>(?<d_assists>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_stls>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_bl>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_tov>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_pf>.*)</td>\r\n\s*<td\s*align="right"\s*>(?<d_pts>.*)</td>\r\n\s*<td\s*align="right"\s*>.*</td>\r\n</tr>#';

	$matches = array();
	preg_match_all($regex, $contents, $matches);
	
	print_r($matches);

	foreach($matches as $st => $val) {
		if(is_string($st)) {
			echo '<h2>' . $st . '</h2>';
			print_r($val);
		}
	}
	echo '<br/><br/>';
	
	$sql= "UPDATE stats SET FG=" . $matches['o_fg'][0] . ", FGA=" . $matches['o_fga'][0] . ", 3P=" . $matches['o_3p'][0] . ", 3PA=" . $matches['o_3pa'][0] . ", FT=" . $matches['o_ft'][0] . ", FTA=" . $matches['o_fta'][0] . ", ORB=" . $matches['o_orb'][0] . ", DRB=" . $matches['o_drb'][0] . ", AST=" . $matches['o_assists'][0] . ", STL=" . $matches['o_stls'][0] . ", BLK=" . $matches['o_bl'][0] . ", TOV=" . $matches['o_tov'][0] . ", PF=" . $matches['o_pf'][0] . ", PTS=" . $matches['o_pts'][0] . ", opp_FG=" . $matches['d_fg'][0] . ", opp_FGA=" . $matches['d_fga'][0] . ", opp_3P=" . $matches['d_3p'][0] . ", opp_3PA=" . $matches['d_3pa'][0] . ", opp_FT=" . $matches['d_ft'][0] . ", opp_FTA=" . $matches['d_fta'][0] . ", opp_ORB=" . $matches['d_orb'][0] . ", opp_DRB=" . $matches['d_drb'][0] . ", opp_AST=" . $matches['d_assists'][0] . ", opp_STL=" . $matches['d_stls'][0] . ", opp_BLK=" . $matches['d_bl'][0] . ", opp_TOV=" . $matches['d_tov'][0] . ", opp_PF=" . $matches['d_pf'][0] . ", opp_PTS=" . $matches['d_pts'][0] . " WHERE team_id=(SELECT id FROM teams WHERE team=\"" . $tm['team'] . "\");";
	if($db->query($sql) === TRUE) {echo $tm['team'] . ' updated.';} else {echo '<p>' . $sql . '</p>';}

	//sleep(10);
}
$db->close();
?>
<p>Done.</p>
</div> <!-- /Content -->
</div> <!-- /Container -->
</body>
</html>