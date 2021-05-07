<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Application\Services\Payload\MatchPlayerParameters;
use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Model\MatchPlayer;
use App\Application\Payload\AbstractRulesToMatch;

class GetMatchPlayersService
{
    private $userRepo;
    
    public function __construct(UserRepositoryInterface $userRepo)
    { 
        $this->userRepo = $userRepo;
    }
    
   public function execute($userLogin, AbstractRulesToMatch $rulesToMatch, $tokens, $beersOrPoints):?MatchPlayer
   {
       //parametry przenieść do nowej klasy (w payload) i tutaj przekazać obiekt $parameters
        $user = null;
        $matchPlayerParameters = new MatchPlayerParameters($rulesToMatch, $tokens);
        $parameters = $matchPlayerParameters->getMatchPlayerParameters($beersOrPoints);
        $user = $this->userRepo->getByLogin($userLogin);
        return new MatchPlayer ($user, $parameters['tokens'], $parameters['points'], $parameters['beers']);
   }
}
