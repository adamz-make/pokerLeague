<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\MatchRepositoryInterface;
use App\Domain\Services\CreateRankingService as CreateRankingDomainService;

class CreateRankingService {
    
    private $matchRepo;
    
    public function __construct (MatchRepositoryInterface $matchRepo)
    {
        $this->matchRepo = $matchRepo;
    }

    public function execute()
    {      
        $matches = $this->matchRepo->getAllMatches();
        $createRankingDomainService = new CreateRankingDomainService();
        $results = $createRankingDomainService->execute($matches);
        
        //$results = $this->resultRepo->getResultsForRanking();
        return $results;
    }
}
