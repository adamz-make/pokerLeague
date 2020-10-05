<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\ResultRepositoryInterface;

class CreateRankingService {

    public function execute(ResultRepositoryInterface $resultRepo)
    {      
        $results = $resultRepo->getResultsForRanking();
        return $results;
    }
}
