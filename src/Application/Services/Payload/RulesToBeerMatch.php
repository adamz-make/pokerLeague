<?php
declare(strict_types=1);

namespace App\Application\Payload;

class RulesToBeerMatch extends RulesToMatch
{
    private $conversionRate; // ile żetonow to 1 piwo
    
    public function getConversionRate()
    {
        return $this->conversionRate;
    }
    
    public function setConversionRate($conversionRate)
    {
        $this->conversionRate = $conversionRate;
    }
}
