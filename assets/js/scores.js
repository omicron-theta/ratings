
$(document).ready(function() {
	$.ajax ({
		url: url,
		type: 'get',
		success: function (data) {
			if (data !== null) {
				sports = parseJSON(data);
				printSports(sports);
			}
		},
		error: function (xhr, ajaxOptions, thrownError){
				alert(xhr.status + ' ' + thrownError);
				return false;
		}
	});
});

	function parseJSON(json) {
		
		var data = [];
		for (i = 0, len = json.sports.length; i < len; i++) {
			var sport = json.sports[i];
			data.push({ id: sport.id, Name: sport.name});
			if (Array.isArray(sport.leagues)) {
				data[i]['leagues'] = Array();
				for (j = 0, len_lg = sport.leagues.length; j < len_lg; j++) {
					leagues = data[i]['leagues'];
					league = sport.leagues[j];
					leagues.push ({abbrev: league.abbreviation, name: league.name, shortName: league.shortName});
				}
			}
		}
		return data;
	}

	function printSports (sports) {
		var html = '<ul>';
		for (i = 0, len = sports.length; i < len; i++) {
			sport = sports[i];
			var sublist = '';
			html += '<li>' + sport.Name + '</li>';
			if (sport.leagues != undefined) {
				html += '<ul>';
				for (j = 0, len = sport.leagues.length; j < len; j++) {
					league = sport.leagues[j];
					html += '<li>' + league.name + '</li>';
				}
				html += '</ul>';
			}
		}
		html += '</ul>';
		$('#response').html(html);
	}

	function getSportGames (sport_id) {
		console.log(sport_id);
	}