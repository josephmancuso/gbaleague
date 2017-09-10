<?php

namespace App\League\Models;

use Mira\Models\Model;

use App\League\Models\Teams;
use App\League\Models\DraftedPokemon;
use Middleware\Authentication;

class Leagues extends Model
{
    public $database = "gbaleague";

    public function nextDrafter()
    {
        $currentDrafter = $this->current()->id;

        $draftOrder = explode(',', $this->draftorder);
        $i = 0;

        foreach($draftOrder as $draftUser) {

            if ($this->ordering == 0){

                if ($draftUser == $currentDrafter){
                    
                    if ($draftOrder[$i+1]) {
                        $nextUser = $draftOrder[$i+1];
                        break;
                    } else {
                        $nextUser = $draftOrder[$i];
                        $order = '1';
                        break;
                    }
                }

            } else {
                // order = 1
                if ($draftUser == $currentDrafter){
                    
                    if ($draftOrder[$i-1]) {
                        $nextUser = $draftOrder[$i-1];
                        break;
                    } else {
                        $nextUser = $draftOrder[$i];
                        $order = '0';
                        break;
                    }
                }
                
            }
            
            $i++;
        }
        $this->current = $nextUser;
        if (isset($order)) {
            $this->ordering = $order;
            $this->round++;
        }
        $this->save();
        var_dump($this);
    }

    public function nextDraftUser() 
    {
        $currentDrafter = $this->current()->id;

        $this->nextDrafter();

        if ($currentDrafter == $this->current) {
            (new $this)->find($this->id)->nextDrafter();
        }
       
    }

    public function draftPokemon($pokemonId)
    {
        $currentUser = (new Authentication)->getCurrentUser();
        $teams = (new Teams)->find("league = '$this->id' AND owner = '$this->current'");
        $pokemon = (new Pokemon)->find($pokemonId);

        $draftedPokemon = new DraftedPokemon;
        $draftedPokemon->team = $teams->id; // $teams
        $draftedPokemon->pokemon = $pokemonId;
        $draftedPokemon->league = $this->id;
        $draftedPokemon->save();

        $teams->points = $teams->points - $pokemon->points;
        $teams->save();
    }
}
