<?php
declare(strict_types=1);

namespace App\Infrastructure\Factory\MatchFactory;

use App\Infrastructure\Factory\MatchFactory\AbstractMatchFactory;
use App\Application\Services\CountTokensToBeersService;
use App\Application\Payload\RulesToBeerMatch;
use App\Domain\Model\TokensCountInterface;

class BeerMatchFactory extends AbstractMatchFactory 
{

    public function getTokensCountService(): TokensCountInterface
    {
        return new CountTokensToBeersService();
    }

    public function getRulesToMatch() 
    {
        return new RulesToBeerMatch();
        
    }

}
