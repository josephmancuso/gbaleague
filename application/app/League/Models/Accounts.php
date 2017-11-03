<?php

namespace App\League\Models;

use Mira\Models\Model;

use App\League\Models\Leagues;

class Accounts extends Model
{
    public $database = "gbaleague";

    public function getLeagues()
    {
        $allLeagues = [];
        $returnLeagues = [];

        $leagues = (new Leagues)->filter(" owner = '$this->id' ");

        foreach($leagues as $league){
            $allLeagues[] = $league->id;
        }

        $teams = (new Teams)->filter("owner = '$this->id' AND league IS NOT NULL");

        foreach($teams as $team){
            $allLeagues[] = $team->league;
        }

        $allLeagues = array_unique($allLeagues);
        

        foreach($allLeagues as $leagueId){
            $returnLeagues[] = (new Leagues)->find($leagueId);
        }

        return $returnLeagues;
    }

    public function isOnTrial()
    {
        if ($this->trial_ends < date('Y-m-d') || $this->customer) {
            return false;
        }

        return true;
    }

    public function trialEnded()
    {
        if ($this->trial_ends < date('Y-m-d') && !$this->customer) {
            return true;
        }

        return false;
    }
}
