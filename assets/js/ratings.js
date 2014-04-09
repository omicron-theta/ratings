rows = 1;
function add_row() {
	rows++;
	console.log(rows);
}	
	
function model_game(vis, home, avg) {
	v_oRtg_adj = parseFloat(vis.oRtg_adj);
	h_oRtg_adj = parseFloat(home.oRtg_adj);
	v_dRtg_adj = parseFloat(vis.dRtg_adj);
	h_dRtg_adj = parseFloat(home.dRtg_adj);
	avg_dRtg = parseFloat(avg.dRtg_adj);
	
	v_pace = parseFloat(vis.pace);
	h_pace = parseFloat(home.pace);
	avg_pace = parseFloat(avg.pace);

	var exp_poss = (v_pace/avg_pace) * (h_pace/avg_pace) * avg_pace;
	
	home = 4;
	
	results = [];
	results['exp_pace'] = exp_poss;
	results['var v_pts'] = exp_poss * (v_oRtg_adj * (h_dRtg_adj/avg_dRtg))/100;
	results['var h_pts'] = home + (exp_poss * (h_oRtg_adj * (v_dRtg_adj/avg_dRtg))/100);
	return results;
}


function ajaxUpdateModel() {
	var row = event.srcElement.id;
	var reg = new RegExp("[a-zA-Z]*");
	//var reg = new RegExp("\d*");
	
	row = row.replace(reg,"");
	console.log(row);
	
	
/*	if (vis_id == null || home_id == null) {
		console.log("One team?");
		return false;
	} else {
		console.log("Two teams");
		return false;		
	}
*/

}