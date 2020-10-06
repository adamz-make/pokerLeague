<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\ResultRepositoryInterface;

class CreateRankingService {
    
    private $resultRepo;
    
    public function __construct (ResultRepositoryInterface $resultRepo)
    {
        $this->resultRepo = $resultRepo;
    }

    public function execute()
    {      
        $results = $this->resultRepo->getResultsForRanking();
        return $results;
    }
}
