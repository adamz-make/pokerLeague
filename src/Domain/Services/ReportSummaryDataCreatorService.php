<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\User;

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
    
    private function getResults($results, $users, $matches)
    {
        $filteredResults = null;
        
        foreach($results as $result)
        {
            foreach($users as $user)
            {
                if ($result->getUserId() === $user->getId())
                {
                    foreach($matches as $match)
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
        
        /*
        foreach ($results as $result)
        {
            if (!empty ($users))
            {
               foreach ($users as $user)
                {
                    if ($result->getUserId() === $user->getId())
                    {
                        $filteredResults[] = $result;
                    } 
                } 
            }
            else
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
        return $filteredResults;   */
    }
    
}
