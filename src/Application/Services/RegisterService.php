<?php
declare (strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Services\RegisterService as RegisterDomainService;
use App\Application\Services\Utils\ValidationHandler;
use App\Domain\Model\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; 


class RegisterService {

    /**
     *
     * @var UserRepositoryInterface
     */
    private $userRepo;
    private $validator;
    private $passwordEncoder;
    
    public function __construct(
            UserRepositoryInterface $userRepo,
            ValidationHandler $validator,
            UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepo = $userRepo;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
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
                $passwordHash = $this->passwordEncoder->encodePassword($user, $password);
                $user->setPassword($passwordHash);
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
