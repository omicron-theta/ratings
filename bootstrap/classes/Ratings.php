<?php
class Rating {
	public $teamName;
	public $conference;
	public $games=0;
	public $mp=0;
	public $fg=0;
	public $fga=0;
	public $tp=0;
	public $tpa=0;
	public $ft=0;
	public $fta=0;
	public $orb=0;
	public $drb=0;
	public $ast=0;
	public $st=0;
	public $bl=0;
	public $tov=0;
	public $pf=0;
	public $pts=0;

	public $d_fg=0;
	public $d_fga=0;
	public $d_3p=0;
	public $d_3pa=0;
	public $d_ft=0;
	public $d_fta=0;
	public $d_orb=0;
	public $d_drb=0;
	public $d_ast=0;
	public $d_st=0;
	public $d_bl=0;
	public $d_tov=0;
	public $d_pf=0;
	public $d_pts=0;
	
	public $opp_mp=0;
	public $opp_fg=0;
	public $opp_fga=0;
	public $opp_3p=0;
	public $opp_3pa=0;
	public $opp_ft=0;
	public $opp_fta=0;
	public $opp_orb=0;
	public $opp_drb=0;
	public $opp_ast=0;
	public $opp_st=0;
	public $opp_bl=0;
	public $opp_tov=0;
	public $opp_pf=0;
	public $opp_pts=0;
	
	public $opp_d_fg=0;
	public $opp_d_fga=0;
	public $opp_d_3p=0;
	public $opp_d_3pa=0;
	public $opp_d_ft=0;
	public $opp_d_fta=0;
	public $opp_d_orb=0;
	public $opp_d_drb=0;
	public $opp_d_ast=0;
	public $opp_d_st=0;
	public $opp_d_bl=0;
	public $opp_d_tov=0;
	public $opp_d_pf=0;
	public $opp_d_pts=0;

	// calculated stats
	public $possessions;
	public $pace;
	public $oRtg;
	public $dRtg;
	
	public $opp_possessions;
	public $opp_pace;
	public $opp_oRtg;
	public $opp_dRtg;
	
	public $oRtg_adj;
	public $dRtg_adj;
	

	function __construct($name) {
		$this->teamName = $name;
	}	
	
// DISPLAY
	public function percent($stat = null) {
		return round($this->$stat*100,2);
	}
	public function num($stat = null, $dec = 1) {
		return round($this->$stat,$dec);
	}

	public function addScore($stats = array()) {
		if (empty($stats)) {
			return false;
		}
		$this->games++;
		$this->mp+=$stats['MP'];
		$this->fg+=$stats['FG'];
		$this->fga+=$stats['FGA'];
		$this->tp+=$stats['3P'];
		$this->tpa+=$stats['3PA'];
		$this->ft+=$stats['FT'];
		$this->fta+=$stats['FTA'];
		$this->orb+=$stats['ORB'];
		$this->drb+=$stats['DRB'];
		$this->ast+=$stats['AST'];
		$this->st+=$stats['ST'];
		$this->bl+=$stats['BL'];
		$this->tov+=$stats['TOV'];
		$this->pf+=$stats['PF'];
		$this->pts+=$stats['PTS'];
		
		$this->d_fg+=$stats['oppFG'];
		$this->d_fga+=$stats['oppFGA'];
		$this->d_3p+=$stats['opp3P'];
		$this->d_3pa+=$stats['opp3PA'];
		$this->d_ft+=$stats['oppFT'];
		$this->d_fta+=$stats['oppFTA'];
		$this->d_orb+=$stats['oppORB'];
		$this->d_drb+=$stats['oppDRB'];
		$this->d_ast+=$stats['oppAST'];
		$this->d_st+=$stats['oppST'];
		$this->d_bl+=$stats['oppBL'];
		$this->d_tov+=$stats['oppTOV'];
		$this->d_pf+=$stats['oppPF'];
		$this->d_pts+=$stats['oppPTS'];
		
		$this->opp_mp+=$stats['opp_MP'];
		$this->opp_fg+=$stats['oppOff_FG'];
		$this->opp_fga+=$stats['oppOff_FGA'];
		$this->opp_3p+=$stats['oppOff_3P'];
		$this->opp_3pa+=$stats['oppOff_3PA'];
		$this->opp_ft+=$stats['oppOff_FT'];
		$this->opp_fta+=$stats['oppOff_FTA'];
		$this->opp_orb+=$stats['oppOff_ORB'];
		$this->opp_drb+=$stats['oppOff_DRB'];
		$this->opp_ast+=$stats['oppOff_AST'];
		$this->opp_st+=$stats['oppOff_ST'];
		$this->opp_bl+=$stats['oppOff_BL'];
		$this->opp_tov+=$stats['oppOff_TOV'];
		$this->opp_pf+=$stats['oppOff_PF'];
		$this->opp_pts+=$stats['oppOff_PTS'];
		
		$this->opp_d_fg+=$stats['oppDef_FG'];
		$this->opp_d_fga+=$stats['oppDef_FGA'];
		$this->opp_d_3p+=$stats['oppDef_3P'];
		$this->opp_d_3pa+=$stats['oppDef_3PA'];
		$this->opp_d_ft+=$stats['oppDef_FT'];
		$this->opp_d_fta+=$stats['oppDef_FTA'];
		$this->opp_d_orb+=$stats['oppDef_ORB'];
		$this->opp_d_drb+=$stats['oppDef_DRB'];
		$this->opp_d_ast+=$stats['oppDef_AST'];
		$this->opp_d_st+=$stats['oppDef_ST'];
		$this->opp_d_bl+=$stats['oppDef_BL'];
		$this->opp_d_tov+=$stats['oppDef_TOV'];
		$this->opp_d_pf+=$stats['oppDef_PF'];
		$this->opp_d_pts+=$stats['oppDef_PTS'];
		
		return true;
		
	}
	
	
	public function calc_stats() {
		// team stats
		$this->possessions=(0.5 * ($this->fga + (0.475*$this->fta) - $this->orb + $this->tov) + 0.5 * ($this->d_fga + (0.475*$this->d_fta) - $this->d_orb + $this->d_tov));
		$this->pace = 40*($this->possessions/(($this->mp)/5));
		$this->oRtg= 100*($this->pts/$this->possessions);
		$this->dRtg = 100*($this->d_pts/$this->possessions);
		
		// opponent stats
		$this->opp_possessions=(0.5 * ($this->opp_fga + (0.475*$this->opp_fta) - $this->opp_orb + $this->opp_tov) + 0.5 * ($this->opp_d_fga + (0.475*$this->opp_d_fta) - $this->opp_d_orb + $this->opp_d_tov));
		$this->opp_pace = 40*($this->opp_possessions/(($this->opp_mp)/5));
		$this->opp_oRtg= 100*($this->opp_pts/$this->opp_possessions);
		$this->opp_dRtg = 100*($this->opp_d_pts/$this->opp_possessions);
	}
	
	public function calc_adj_stats() {
		//  adjusted offensive rating (for opponents' defensive ratings)
		$opp_dRtg_pct = 1-(($this->opp_dRtg)/AVG_OPP_ORTG);
		$this->oRtg_adj = $this->oRtg + ($this->oRtg * $opp_dRtg_pct);
		
		//  adjusted defensive rating (for opponents' offensive ratings)
		$opp_oRtg_pct = 1-(($this->opp_oRtg)/AVG_OPP_DRTG);
		$this->dRtg_adj = $this->dRtg + ($this->dRtg * $opp_oRtg_pct);
	}
	
	public function pyth() {
		$pyth = (pow($this->oRtg_adj, 10.25))/(pow($this->oRtg_adj, 10.25)+pow($this->dRtg_adj, 10.25));
		$pyth = round($pyth,4);
		return $pyth;
	}
	
}