<?php

namespace App\Tournaments\Models;

use App\League\Models\Leagues;

class Tournaments
{
    public function __construct()
    {
        $this->tournaments = [];

        $leagues = new Leagues;

        foreach($leagues->filter("tournament = '1'") as $tournament) {
            $this->tournaments[] = $leagues->find($tournament->id);
        }
    }

    public function getTournaments()
    {
        return $this->tournaments;
    }
        
}
