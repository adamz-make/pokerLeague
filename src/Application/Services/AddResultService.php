<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\Result;
use App\Domain\Services\Utils\AddResultService as DomainAddResultService;
//nie mam meczu dodaj mecz, i dodaj wynik dla użytkownika
// przekaz usera i match


class AddResultService {
    
    private $user;
    private $match;
    private $params =[];
    
    /**
     * 
     * @param User $user
     * @param Match $match
     * @param type $params
     */
    public function __construct(User $user, Match $match, $params)
    {
        $this->user = $user;
        $this->match = $match;
        $this->params = $params;
    }
    
    public function execute(ResultRepositoryInterface $resultRepo )
    {
        $domainAddResultService = new DomainAddResultService();
        $result =  $domainAddResultService->execute($this->user, $this->match, $this->params);
        $resultRepo->add($result);
    }
}
