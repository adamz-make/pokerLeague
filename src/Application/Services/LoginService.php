<?php
declare(strict_types=1);

namespace App\Application\Services;

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
    
    public function execute(string $login, string $password) 
    {
        $user = $this->userRepo->getByLogin($login);
        $loginService = new LoginDomainService();
        if ($user != null)
        {
            return $loginService->execute($user,$password);
        }
        return null;
    }
}
