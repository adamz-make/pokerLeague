<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Services\Utils\AddResultService as DomainAddResultService;

//jeżeli użytkownik ma już dodany wynik do meczu to nie dodawaj kolejnego

class AddResultService {
    
    private $resultRepo;
    
    /**
     * 
     * @param User $user
     * @param Match $match
     * @param type $params
     */
    public function __construct(ResultRepositoryInterface $resultRepo )
    {
        $this->resultRepo = $resultRepo;
    }
    
    public function execute(User $user, Match $match, $params )
    {
        $domainAddResultService = new DomainAddResultService();
        $result =  $domainAddResultService->execute($user, $match, $params);
        if (!$this->resultRepo->isResultForUserAdded($result))
        {
            $this->resultRepo->add($result);
            return $result;
        }
    }
    
        
}
