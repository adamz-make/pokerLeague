<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Services\GetMatchPlayersService as GetMatchPlayersDomainService;
use App\Domain\Model\UserRepositoryInterface;

class GetMatchPlayersService
{
    private $userRepo;
    
    public function __construct(UserRepositoryInterface $userRepo)
    { 
        $this->userRepo =$userRepo;
    }
    
   public function execute($parameters): ?array
   {
       $users = null;
       foreach (array_keys($parameters) as $key)
       {
           if (substr($key, 0, strlen('user')) === 'user')
           {
               $users[] = $this->userRepo->getByLogin($parameters[$key]);
           }
       }
       $getMatchPlayersDomainService = new GetMatchPlayersDomainService();
       return $getMatchPlayersDomainService->execute($users, $parameters);
   }
}
