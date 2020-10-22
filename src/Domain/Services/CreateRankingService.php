<?php
declare (Strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Result;
use App\Domain\Model\TotalResultForUser;
use App\Domain\Model\Match;

class CreateRankingService
{
    public function execute ($matches)
    {
        $totalResults = null;
        
        foreach($matches as $match)
        {
            foreach($match->getMatchPlayers() as $matchPlayer)
            {
                $totalResults[$matchPlayer->getUser()->getLogin()]['beers'] = 0;
                $totalResults[$matchPlayer->getUser()->getLogin()]['points'] = 0;
                $totalResults[$matchPlayer->getUser()->getLogin()]['tokens'] = 0;   
            }
        }       
        foreach ($matches as $match)
        {
            foreach ($match->getMatchPlayers() as $matchPlayer)
            {//$matchplayer[$matchplayer[beers,points,tokens]]
                $totalResults[$matchPlayer->getUser()->getLogin()]['beers'] += $matchPlayer->getBeers();
                $totalResults[$matchPlayer->getUser()->getLogin()]['points'] += $matchPlayer->getPoints();
                $totalResults[$matchPlayer->getUser()->getLogin()]['tokens'] += $matchPlayer->getTokens();
                //$results[] = new Result (null, $matchPlayer->getUser()->getId(), $match->getMatchId(), $matchPlayer->getPoints(), $matchPlayer->getBeers(),
                  //                      $matchPlayer->getTokens()); 
            }
        }
        
        $addedTotalResultForUser = null;
        foreach ($matches as $match)
        {
            foreach ($match->getMatchPlayers() as $matchPlayer)
            {
                if (!empty($addedTotalResultForUser))
                {
                    $addTotalResult = true;
                    foreach ($addedTotalResultForUser as $addedUser)
                    {
                        if ($matchPlayer->getUser()->getLogin() === $addedUser)
                        {
                            $addTotalResult = false;
                        }
                    }
                    if ($addTotalResult)
                    {
                        $totalResultforUsers[] = new TotalResultForUser($matchPlayer->getUser(),$totalResults[$matchPlayer->getUser()->getLogin()]['beers'],
                            $totalResults[$matchPlayer->getUser()->getLogin()]['points'],$totalResults[$matchPlayer->getUser()->getLogin()]['tokens']);
                    $addedTotalResultForUser[] = $matchPlayer->getUser()->getLogin();
                    }
                }
                else
                {
                    $totalResultforUsers[] = new TotalResultForUser($matchPlayer->getUser(),$totalResults[$matchPlayer->getUser()->getLogin()]['beers'],
                            $totalResults[$matchPlayer->getUser()->getLogin()]['points'],$totalResults[$matchPlayer->getUser()->getLogin()]['tokens']);
                    $addedTotalResultForUser[] = $matchPlayer->getUser()->getLogin();
                }
            }
        }
        return $totalResultforUsers;
    }
}
