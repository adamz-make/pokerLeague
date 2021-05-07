<?php
declare(strict_types=1);

namespace App\Application\Payload;

use App\Domain\Model\RulesMatchInterface;

class RulesToLeagueMatch extends AbstractRulesToMatch implements RulesMatchInterface
{
    public function setConversionRate($conversionRate = null)
    {
        $this->conversionRate = [0 => 50, 1 => 25, 2 => 10];
    }
   
}
