<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\User;
use App\Domain\Services\Utils\NotCorrrectFiltersException;

class ReportSummaryDataCreatorService {
    
    public function Execute($matches, $users, $results, $filters)
    {
        $filteredMatches = null;
        $filteredUsers = null;
        $filteredResults = null;
        $filteredMatches = $this->getMatches($matches, $filters['dateFrom'], $filters['dateTo']);
        $filteredUsers = $this->getUsers($users, $filters['users']);
        $filteredResults = $this->getResults($results, $filteredUsers, $filteredMatches);
        
        
        // i z dwóch powyższych powybierać Rezultaty
        
        $data = ['Matches' => $filteredMatches, 'Users' => $filteredUsers, 'Results' => $filteredResults];
        return $data;
    }
    
    private function getUsers ($users, $filteredUsers)
    {
        $usersToReturn = null;
        if (empty ($filteredUsers))
        {
            $usersToReturn = $users;
        }
        else
        {
            foreach ($users as $user)
            {
                foreach ($filteredUsers as $filteredUser)
                {
                    if ($user->getLogin() === $filteredUser)
                    {
                        $usersToReturn[] = $user;
                    }
                }
            }
        }
        return $usersToReturn;
    }
    
    private function getMatches($matches, $dateFrom, $dateTo)
    {
        $filteredMatches = null;
        foreach($matches as $match)
        {
            if ($dateFrom !== null && $dateTo !== null)
            {
                if ($match->getDateOfMatch() >= $dateFrom && $match->getDateOfMatch() < $dateTo)
                {
                    $filteredMatches[] = $match;
                }
            }
            elseif ($dateFrom === null && $dateTo !== null)
            {
                if ($match->getDateOfMatch() < $dateTo)
                {
                    $filteredMatches[] = $match;
                }
            }
            elseif ($dateFrom !== null && $dateTo === null)
            {
                if ($match->getDateOfMatch() >= $dateFrom)
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
    
    private function getResults($results, $filteredUsers, $filteredMatches)
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
