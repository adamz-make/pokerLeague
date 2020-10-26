<?php
declare(strict_types=1);

namespace App\Infrastructure\Factory\MatchFactory;

use App\Infrastructure\Factory\MatchFactory\AbstractMatchFactory;
use App\Application\Services\CountTokensToPointsService;
use App\Application\Payload\RulesToLeagueMatch;
use App\Domain\Model\TokensCountInterface;


class LeagueMatchFactory extends AbstractMatchFactory 
{

    public function getTokensCountService(): TokensCountInterface
    {
        return new CountTokensToPointsService();
    }

    public function getRulesToMatch() 
    {
        return new RulesToLeagueMatch;
    }

}
