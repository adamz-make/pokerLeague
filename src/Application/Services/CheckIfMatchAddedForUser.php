<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\Result;

class CheckIfMatchAddedForUser {
    private $user;
    private $match;
    
    public function __construct(User $user, Match $match)
    {
        $this->user = $user;
        $this->match = $match;
    }
    
    public function execute(ResultRepositoryInterface $resultRepo)
    {
        $result = new Result(null,$this->user->getId(),$this->match->getMatchId(),null,null,null);
        return $resultRepo->isResultForUserAdded($result);
    }
}
