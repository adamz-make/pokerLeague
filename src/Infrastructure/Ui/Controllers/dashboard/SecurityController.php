<?php
declare (strict_types=1);

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Application\Services\Utils\ValidationHandler;
use App\Infrastructure\Model\UserRepository;

class SecurityController extends AbstractController {
    
    /**
    * @Route(path="/home/logout", name="logout")
    */
    public function logout()
    {
        
    }
    
     /**
     * @Route(path="/home/login", name="login")
     */
    
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();
        return $this->render('dashboard/login.html.twig',[
            'lastUserName' => $lastUserName,
            'error' => $error
        ]);
    }
    
    /**
    * @Route(path="/home/register", name="register")
    */   
    public function register(UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $login = $_POST['login'];
            $password = $_POST['pass'];
            $checkPassword = $_POST['pass2'];
            $mail = $_POST['mail'];
            $validationHandler = new ValidationHandler();
            $registerService = new \App\Application\Services\RegisterService(new UserRepository(),$validationHandler, $passwordEncoder);
            try
            {
                if (($user = $registerService->execute($login, $mail, $password, $checkPassword)) !== null)
                {
                    return $this->render('dashboard/register.html.twig',[
                       'addedNewUser' => "Dodano użytkownika $login"
                       ]);
                }
                else
                {
                    return $this->render('dashboard/register.html.twig', array(
                       'validationErrors' => $validationHandler->getErrorMesages()
                       ));
                }   
            } 
            catch (RegisterLogicException $ex) 
            {
                return $this->render('dashboard/register.html.twig', array(
                       'validationErrors' => [$ex->getMessage()]
                       ));
            }
        }
        return $this->render('dashboard/register.html.twig');
    }
}
