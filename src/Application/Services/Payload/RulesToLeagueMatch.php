<?php
declare(strict_types=1);

namespace App\Application\Payload;

class RulesToLeagueMatch extends AbstractRulesToMatch
{
    public function setConversionRate($conversionRate = null)
    {
        $this->conversionRate = [0 => 50, 1 => 25, 2 => 10];
    }
   
}
