<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\Match;

class ReportSummaryDataCreatorService {
    
    public function Execute(Match $matches, $users, $results, $filters)
    {
        $filteredMatches = null;
        $filteredUsers = null;
        $filteredResults = null;
        $filteredMatches = $this->getMatches($matches, $filters['dateFrom'], filters['dateTo']);
        
        //users
        
        // i z dwóch powyższych powybierać Rezultaty
        
        $data = [$filteredMatches, $users, $results];
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
}
