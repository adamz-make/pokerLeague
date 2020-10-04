<?php
declare (strict_types=1);

namespace App\Domain\Services\Utils;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\Result;

class AddResultService {
    
    public function execute(User $user, Match $match, $params)
    {
        return new Result(null, $user->getId(), $match->getMatchId(), $params['points'], $params['beers'], $params['tokens']);
           
    }
}
