<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\User;
use App\Domain\Services\Utils\NotCorrrectFiltersException;
use App\Domain\Model\MatchPlayer;
use App\Domain\Model\ObjectSummaryReport;
use App\Domain\Model\SummaryReport;

class ReportSummaryDataCreatorService {
    
    public function execute($matches, $users)
    {
        $filteredMatches = $matches;
        $filteredUsers = $users;
        $summaryReportObject = $this->getDataForReport($filteredMatches);
        $filteredSummaryReportObject = [];
        foreach ($summaryReportObject as $object)
        {
            foreach ($filteredUsers as $user)
            {
                if ($object->getUserName() === $user->getLogin())
                {
                    $filteredSummaryReportObject[] = $object;
                } 
            }
        }
            $summaryReport = new SummaryReport();
            $summaryReport->setSummaryObjectReports($filteredSummaryReportObject);
        return $summaryReport;
    }
    
    private function getDataForReport($filteredMatches)
    {
        $objectSummaryReport = [];
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
                //sprobowac dodać klase Summary Report, w której $objectSummaryReport byłby polem, tak abym mógł zwrócić jeden obiekt, który miał by
                // pole $objectSummaryReport z arrayem wszystkich objectSummaryReport
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
