<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\Match;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Services\Utils\AddResultService as DomainAddResultService;

//jeżeli użytkownik ma już dodany wynik do meczu to nie dodawaj kolejnego

class AddResultService {
    
    private $resultRepo;
    
    public function __construct(ResultRepositoryInterface $resultRepo )
    {
        $this->resultRepo = $resultRepo;
    }
    
    public function execute(Match $match)
    {
        $domainAddResultService = new DomainAddResultService();
        $results =  $domainAddResultService->execute($match);
        foreach($results as $result)
        {
            $this->resultRepo->add($result);
            
        }
    }
    
        
}
