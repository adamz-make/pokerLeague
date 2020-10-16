<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\User;
use App\Domain\Services\Utils\NotCorrrectFiltersException;
use App\Domain\Model\MatchPlayer;
use App\Domain\Model\ObjectSummaryReport;

class ReportSummaryDataCreatorService {
    
    public function execute($matches, $users, $results)
    {
        $filteredMatches = $matches;
        $filteredUsers = $users;
        $filteredResults = $this->getResults($results, $filteredUsers, $filteredMatches);
        foreach ($filteredResults as $filteredResult)
        {
            $userId = $filteredResult->getUserId();
            $user = $this->getUser($filteredUsers, $userId);
            $matchId = $filteredResult->getMatchId();
            $match = $this->getMatch($filteredMatches, $matchId);
            $match->addMatchPlayer(new MatchPlayer($user, $filteredResult->getTokens(), $filteredResult->getPoints(), $filteredResult->getBeers()));       
        }
        // filtered Matches mam mecze razem match playersami (czyli kto grał i ile żetonów)
        
        
        $data = $this->getDataForReport($filteredMatches);    
        return $data;
    }
    
    private function getDataForReport($filteredMatches)
    {
       foreach ($filteredMatches as $match)
       {
           foreach ($match->getMatchPlayers() as $matchPlayer)
        {
            $cumulatedPoints[$matchPlayer->getUser()->getLogin()] = 0;
            $cumulatedBeers[$matchPlayer->getUser()->getLogin()] = 0;
            $cumulatedTokens[$matchPlayer->getUser()->getLogin()] = 0;
        }
       }
        
        
        foreach ($filteredMatches as $match)
        {
            foreach ($match->getMatchPlayers() as $matchPlayer)
            {
                $nrMatch = $match->getMatchNr();
                $userName = $matchPlayer->getUser()->getLogin();
                $beers = $matchPlayer->getBeers();
                $points = $matchPlayer->getPoints();
                $tokens = $matchPlayer->getTokens();
                $cumulatedPoints[$matchPlayer->getUser()->getLogin()] += $points;
                $cumulatedBeers[$matchPlayer->getUser()->getLogin()] += $beers;
                $cumulatedTokens[$matchPlayer->getUser()->getLogin()] += $tokens;
                $objectSummaryReport[] = new ObjectSummaryReport($nrMatch, $userName, $beers, $tokens, $points,$cumulatedBeers[$matchPlayer->getUser()->getLogin()],
                        $cumulatedTokens[$matchPlayer->getUser()->getLogin()], $cumulatedPoints[$matchPlayer->getUser()->getLogin()]);
            }
        }
        return $objectSummaryReport;
        
    }
    
    /**
     * 
     * @param type $filteredMatches
     * @param type $matchId
     * @return ?Match
     */
    private function getMatch($filteredMatches, $matchId)
    {
        foreach ($filteredMatches as $match)
        {
            if ($match->getMatchId() === $matchId)
            {
                return $match;
            }
        }
        return null;
    }
    
    
    /**
     * 
     * @param type $filteredUsers
     * @param type $userId
     * @return ?User
     */
    private function getUser($filteredUsers, $userId)
    {
        foreach ($filteredUsers as $user)
        {
            if ($user->getId() === $userId)
            {
                return $user;
            }
        }
        return null;
    }
    
    private function getResults($results, $filteredUsers, &$filteredMatches)
    {
        $filteredResults = null;
        if (empty($filteredUsers))
        {
            Throw new NotCorrrectFiltersException ('Niepoprawne parametry filtru');
        }
        if (empty ($filteredMatches))
        {
            Throw new NotCorrrectFiltersException ('Niepoprawne parametry filtru');
        }       
        foreach($results as $result)
        {
            foreach($filteredUsers as $user)
            {
                if ($result->getUserId() === $user->getId())
                {
                    foreach($filteredMatches as $match)
                    {
                        if ($result->getMatchId() === $match->getMatchId())
                        {
                            $filteredResults[] = $result;
                        }
                    }
                }
            }                  
        }
        return $filteredResults; 
    } 
}
