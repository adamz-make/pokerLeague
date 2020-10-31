<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\TokensCountInterface;
use App\Application\Payload\AbstractRulesToMatch;

class CountTokensToBeersService
{
    public function execute(AbstractRulesToMatch $rulesToMatch, $user, $users, $tokens)
    {
        $tokensOnStart = $rulesToMatch->getTokensOnStart();
        $conversionRate = $rulesToMatch->getConversionRate();
        return ($tokens - $tokensOnStart)/$conversionRate;  
    }

}
