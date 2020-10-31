<?php
declare(strict_types=1);

namespace App\Application\Services;

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
    
   public function execute($userLogin, AbstractRulesToMatch $rulesToMatch, $tokens):?MatchPlayer
   {
       //parametry przenieść do nowej klasy (w payload) i tutaj przekazać obiekt $parameters
        $user = null;
        $user = $this->userRepo->getByLogin($userLogin);
        return new MatchPlayer ($user, $tokens, $points, $beers);
   }
}
