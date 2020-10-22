<?php
declare (strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\User;
use App\Domain\Model\MatchPlayer;

class GetMatchPlayersService 
{
    public function execute($users, $parameters)
    {
        foreach ($users as $user)
        {
         $userName = $user->getLogin();
         $matchPlayers[] = $this->getMatchPlayerFromParameters($userName,$parameters);
      
            
        }
        
        return $matchPlayers;
    }
    
    private function getMatchPlayerFromParameters($userName, $parameters)
    {
        foreach ($parameters as $parameter)
        {
            if (parameter === $userName)
            {
                $number = substr(key($parameters),strlen(key($parameters)) - 1, 1);
                $matchPlayer = new MatchPlayer
                
                $data =["beers" => $parameters["beers_ $number"], ["points" => $parameters["points_ $number"], ["tokens" => $parameters["tokens_ $number"] ]
            }
        }
        
        
    }
    
}
