<?php
declare(strict_types=1);

namespace App\Application\Payload;

use App\Domain\Model\RulesMatchInterface;

class RulesToBeerMatch extends AbstractRulesToMatch implements RulesMatchInterface
{
    public function setConversionRate($conversionRate = null)
    {
        $this->conversionRate = $conversionRate;
    }
}
