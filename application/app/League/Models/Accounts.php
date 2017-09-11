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
        $leagues = (new Leagues)->filter(" owner = '$this->id' ");

        foreach($leagues as $league){
            $allLeagues[] = $league->id;
        }

        $teams = (new Teams)->filter("owner = '$this->id' AND league IS NOT NULL");

        foreach($teams as $team){
            $allLeagues[] = $team->league;
        }

        return array_unique($allLeagues);
    }
}
