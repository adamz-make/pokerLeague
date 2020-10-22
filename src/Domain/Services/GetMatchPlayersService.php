<?php
declare (strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\User;
use App\Domain\Model\MatchPlayer;

class GetMatchPlayersService 
{
    public function execute($users, $parameters)
    {
        foreach ($users as $user)
        {
         $matchPlayers[] = $this->getMatchPlayerFromParameters($user,$parameters);   
        }   
        return $matchPlayers;
    }
    
    private function getMatchPlayerFromParameters(User $user, $parameters): ?MatchPlayer
    {
        $matchPlayer = null;
        foreach (array_keys($parameters) as $key)
        {
            if ($parameters[$key] === $user->getLogin())
            {
                $number = substr($key,strlen($key) - 1, 1);   
                $matchPlayer = new MatchPlayer($user,$parameters["tokens_$number"],$parameters["points_$number"],$parameters["beers_$number"]); 
            }
        }
        return $matchPlayer;
    }
    
}
