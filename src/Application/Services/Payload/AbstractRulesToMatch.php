<?php
declare(strict_types=1);

namespace App\Application\Payload;

Abstract class RulesToMatch 
{
    private $tokensOnStart;
    
    public function  getTokensOnStart()
    {
        return $tokensOnStart;
    }
    
    public function setTokensOnStart($tokensOnStart)
    {
        $this->tokensOnStart = $tokensOnStart;
    }
    
    
    

}
