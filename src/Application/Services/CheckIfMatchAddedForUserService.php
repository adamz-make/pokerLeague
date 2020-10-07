<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\Result;
//userMatchResult
class CheckIfMatchAddedForUserService {
    
    private $resultRepo;
    
    public function __construct(ResultRepositoryInterface $resultRepo)
    {
       $this->resultRepo = $resultRepo;
    }
    
    public function execute(User $user, Match $match)
    {
        $result = new Result(null,$user->getId(),$match->getMatchId(),null,null,null);
        return $this->resultRepo->isResultForUserAdded($result);
    }
}
