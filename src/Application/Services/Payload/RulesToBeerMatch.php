<?php
declare(strict_types=1);

namespace App\Application\Payload;

class RulesToBeerMatch extends AbstractRulesToMatch
{
    public function setConversionRate($conversionRate = null)
    {
        $this->conversionRate[] = $conversionRate;
    }
}
