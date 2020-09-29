<?php

namespace App\Application\services;
use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Services\LoginService as LoginDomainService;

class LoginService{
    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepo;
    
    
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    
    public function execute(string $login,string $password) 
    {
        $user = $this->userRepo->getBy('login',$login);
        $loginService = new LoginDomainService();
        return $loginService->execute($user,$password);
    }
}
