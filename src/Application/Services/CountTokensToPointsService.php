<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\TokensCountInterface;
use App\Application\Payload\AbstractRulesToMatch;

class CountTokensToPointsService
{
    public function execute(AbstractRulesToMatch $rulesToMatch, $user, $users, $tokens) 
    {
        $key = array_keys($users, $user);
        $points = $tokens[$key];
        $numberKey = array_keys($tokens, $points);
        $pointsFromRulesMatch = $rulesToMatch->getConversionRate();
        return $pointsFromRulesMatch[$numberKey];        
    }

}
