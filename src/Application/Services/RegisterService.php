<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Services\RegisterService as RegisterDomainService;
use App\Application\Services\Utils\ValidationHandler;
use App\Domain\Model\User;

class RegisterService {

    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepo;
    private $validator;
    
    public function __construct(UserRepositoryInterface $userRepo, ValidationHandler $validator)
    {
        $this->userRepo = $userRepo;
        $this->validator = $validator;
    }
    
    public function execute($login, $mail, $password, $checkPassword)
    {
        $array = ['Login' => $login, 'Mail' => $mail, 'Haslo' => $password, 'Haslo sprawdzajace' => $checkPassword];
        foreach($array as $key => $val)
        {
            if (empty($val))
            {
                $this->validator->handleError ("Uzupełnij $key");
                next($array);
            }
        }
        if (!$this->validator->hasErrors())
        {
            $user = $this->userRepo->userExists($login, $mail);
            if ($user === null)
            {
                $user = new User(null, $login, $password, $mail);
                $registerDomainService = new RegisterDomainService();
                if(($registerDomainService->execute($login, $mail, $password, $checkPassword)))
                {
                    $this->userRepo->register($user);
                    return $user;
                }
            }
            else
            {
                $this->validator->handleError("Użytkownik o podanym loginie lub mailu już istnieje");
            }
        }
           
        return null;
    }
}
