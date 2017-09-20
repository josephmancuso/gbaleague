<?php

namespace App\Tournaments\Models;

use App\League\Models\Leagues;

class Tournaments
{
    public function __construct()
    {
        
    }

    public function getTournaments()
    {
        return $this->tournaments;
    }

        
}
