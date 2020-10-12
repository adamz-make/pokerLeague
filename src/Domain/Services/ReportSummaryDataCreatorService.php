<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\User;

class ReportSummaryDataCreatorService {
    
    public function Execute(Match $matches, $users, $results, $filters)
    {
        $filteredMatches = null;
        $filteredUsers = null;
        $filteredResults = null;
        $filteredMatches = $this->getMatches($matches, $filters['dateFrom'], filters['dateTo']);
        $filteredUsers = $users; //są już pobrani wyfiltorwani Userzy
        $filteredResults = $this->getResults($results, $filteredUsers, $filteredMatches);
        
        
        // i z dwóch powyższych powybierać Rezultaty
        
        $data = ['Matches' => $filteredMatches, 'Users' => $filteredUsers, 'Results' => $filteredResults];
        return $data;
    }
    
    private function getMatches(Match $matches, $dateFrom, $dateTo)
    {
        $filteredMatches = null;
        foreach($matches as $match)
        {
            if ($filters['dateFrom'] !== null && filters['DateTo'] !== null)
            {
                if ($match->getDateOfMatch() >= $filters['dateFrom'] && $match->getDateOfMatch() < $filters['dateTo'])
                {
                    $filteredMatches[] = $match;
                }
            }
            elseif ($filters['dateFrom'] === null && filters['DateTo'] !== null)
            {
                if ($match->getDateOfMatch() < $filters['dateTo'])
                {
                    $filteredMatches[] = $match;
                }
            }
            elseif ($filters['dateFrom'] !== null && filters['DateTo'] === null)
            {
                if ($match->getDateOfMatch() >= $filters['dateFrom'])
                {
                    $filteredMatches[] = $match;
                }
            }
            else
            {
                $filteredMatches[] = $match; 
            }            
        }       
        return $filteredMatches;
    }
    
    private function getResults(Result $results, User $users, Match $matches)
    {
        $filteredResults = null;
        foreach ($results as $result)
        {
            foreach ($users as $user)
                if ($result->getUserId() === $user->getId ())
                {
                    $filteredResults[] = $result;
                }
        }
        foreach($results as $result)
        {
            foreach ($matches as $match)
            {
                if ($result->getMatchId() === $match->getMatchId())
                {
                    $addResult = true;
                    foreach ($filteredResults as $filteredResult) // sprawdzić trzeba, czy już rezultat nie został dodany w userze (żeby nie dublować)
                    {
                        if ($result->getId() === $filteredResult->getId())
                        {
                            $addResult = false;
                        }
                    }
                    if ($addResult === true)
                    {
                        $filetedResults[] = $result;
                    }
                }
            }
        }
        return $filteredResults;   
    }
    
}
