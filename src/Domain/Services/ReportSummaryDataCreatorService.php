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
            data[] = 
        }
        
        dd($filteredResults);
        exit;
        // i do wyfiltrowanych userow i wyfiltrowanych meczy powybierać Rezultaty        
        return $data;
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
                            //$match->addMatchPlayer(new MatchPlayer($user, $result->getTokens(),$result->getPoints(), $result->getBeers()));
                        }
                    }
                }
            }                  
        }
        return $filteredResults;

        /*
        foreach ($filteredMatches as $match)
        {
            if (!empty($match->getMatchPlayers()))
            {
                foreach ($match->getMatchPlayers() as $player)
                {
                    $cumulatedBeers[$player->getUser()->getLogin()] = 0;
                    $cumulatedTokens[$player->getUser()->getLogin()] = 0;
                    $cumulatedPoints [$player->getUser()->getLogin()] = 0;    
                }  
            }
                
            
        }
      
        foreach ($filteredMatches as $match)
        {
            foreach ($match->getMatchPlayers() as $player)
            {
                if (empty($data))
                {
                   $userName = $player->getUser()->getLogin();
                   $data[] = new ObjectSummaryReport($match->getMatchNr(), $userName, $player->getBeers(), $player->getTokens(), $player->getPoints(),
                           $player->getBeers(), $player->getTokens(), $player->getPoints()); 
                }
                else
                {
                    foreach ($data as $objSummary)
                    {
                        if ($objSummary->getUserName() === $userName)
                        {
                            $cumulatedBeers[$player->getUser()->getLogin()] += $objSummary->getBeers();
                            $cumulatedTokens[$player->getUser()->getLogin()] += $objSummary->getTokens();
                            $cumulatedPoints[$player->getUser()->getLogin()] += $objSummary->getPoints();
                        }
                    }
                    $data[] = new ObjectSummaryReport($match->getMatchNr(), $userName, $player->getBeers(), $player->getTokens(), $player->getPoints(),
                           $cumulatedBeers[$player->getUser()->getLogin()], $cumulatedTokens[$player->getUser()->getLogin()],
                            $cumulatedPoints[$player->getUser()->getLogin()]); 
                }
              
            }
            
        }
        
        dd($data);
        exit;
        return $data;
         * */
    
    } 
}
