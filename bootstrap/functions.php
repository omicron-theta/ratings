<?php

// GLOBALS
	DEFINE('JS', 'assets/js/');
	DEFINE('CSS', 'assets/css/');
	DEFINE('HOME', 4);

	echo css(array('reset', 'styles'));	
	echo js(array('jquery-1.7.2','tablesorter','tablesorter.widgets','ratings')); 

// LOADING ASSETS

function css($files = array()) {
	if (!empty($files)) {
		foreach($files as $file) {
			echo '<link rel="stylesheet" type="text/css" href="' . CSS . $file . '.css"/>';
		}
	}
}

function js($files = array()) {
	if (!empty($files)) {
		foreach($files as $file) {
			echo '<script src="' . JS . $file . '.js"></script>';
		}
	}
}

//  FUNCTIONS

function getSchoolGames($school_id = int, $row = array()) {
	if ($row['id'] === $school_id) {return true;} else {return false;}
}

function set_avgs($teams = array()) {
// calculate and set global variables for d1 averages
//i.e. pace, oRtg, dRtg, opp_oRtg, opp_dRtg
	
	$stats = array ('pace', 'oRtg', 'dRtg', 'opp_oRtg', 'opp_dRtg');
	foreach($stats as $k => $stat) {
		$count=0;
		$n=0;
		$gbl= "AVG_" . strtoupper($stat);
		foreach($teams as $team) {
			$count++;
			$n += $team->$stat;
		}
		define($gbl,$n/$count);
	}
}