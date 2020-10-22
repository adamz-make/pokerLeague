<?php
declare (strict_types=1);

namespace App\Domain\Services\Utils;

use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\Result;
use App\Domain\Model\MatchPlayer;

class AddResultService {
    
    public function execute(Match $match)
    {
        foreach ($match->getMatchPlayers() as $matchPlayer)
        {
            $result[] = new Result (null, $matchPlayer->getUser()->getId(), $match->getMatchId(), $matchPlayer->getPoints(), $matchPlayer->getBeers(),
                                    $matchPlayer->getTokens());
        }    
        return $result;
    }
}
