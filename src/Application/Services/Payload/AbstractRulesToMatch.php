<?php
declare(strict_types=1);

namespace App\Application\Payload;

Abstract class AbstractRulesToMatch
{
    private $tokensOnStart;
    protected $conversionRate;
    
    public function  getTokensOnStart()
    {
        return $this->tokensOnStart;
    }
    
    public function setTokensOnStart($tokensOnStart)
    {
        $this->tokensOnStart = $tokensOnStart;
    }
    
     public function getConversionRate()
    {
        return $this->conversionRate;
    }
    
    abstract public function setConversionRate($conversionRate = null);
}
