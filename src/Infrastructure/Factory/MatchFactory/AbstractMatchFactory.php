<?php
declare (strict_types=1);

namespace App\Infrastructure\Factory\MatchFactory;

use App\Domain\Model\RulesMatchInterface;
use App\Domain\Model\TokensCountInterface;

abstract class AbstractMatchFactory 
{
   private const BEER_MATCH = 'meczNaPunktyChecked';
   private const LEAGUE_MATCH = 'meczLigowyChecked';
   
   static function getFactory($matchType)
   {
       switch ($matchType)
       {
           case self::BEER_MATCH:
               return new BeerMatchFactory();
           case self::LEAGUE_MATCH:
               return new LeagueMatchFactory();
       }

   }   
   abstract function getTokensCountService(): TokensCountInterface;
   abstract function getRulesToMatch() : RulesMatchInterface;
   
    
}
