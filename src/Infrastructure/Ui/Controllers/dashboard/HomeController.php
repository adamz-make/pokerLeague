<?php

declare (strict_types=1);

namespace App\Infrastructure\Ui\Controllers\dashboard;

use App\Domain\Model\User;
use App\Infrastructure\Model\UserRepository;
use App\Application\Services\LoginService;
use App\Application\Services\Utils\ValidationHandler;
use App\Domain\Services\Utils\RegisterLogicException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController{
//class HomeController extends \App\Infrastructure\Ui\Controllers\AbstractController{
    /**
     * @Route(path="/home", name="home")
     */
    public function index()
    {
        //Przekazanie zmiennej do widoku
        /*$this->render('dashboard/index.html.twig',array(
            'zmienna' => 'przekazalem',
        ));
         */
        return $this->render('dashboard/index.html.twig');
    }
    
    /**
     * @Route(path="/home/login", name="login")
     */
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $login = $_POST['login'];
            $password = $_POST['pass'];
            $loginService = new LoginService(new UserRepository());
            if (($user = $loginService->execute($login, $password)) != null)
            {
                $_SESSION['user']=json_encode($user);
                header('Location: /dashboard/home/loggedin'); 
                exit;
            }
            else
            {
                $this->render('dashboard/login.html.twig', array(
                'notLoggedIn' => 'Niepoprawne login lub hasło. Spróbuj ponownie'
                ));
                exit;
            }
        }
       return $this->render('dashboard/login.html.twig',[
            'notLoggedIn' => ''
        ]);
    }
   /**
    * @Route(path="/dashboard/home/loggedin", name="loggedin")
    */ 
    public function loggedin()
    {
        if ($this->getUser() == null)
        {
            header('Location: /home');
            exit;
        }
        $this->render('dashboard/loggedin.html.twig');
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $login = $_POST['login'];
            $password = $_POST['pass'];
            $checkPassword = $_POST['pass2'];
            $mail = $_POST['mail'];
            $validationHandler = new ValidationHandler();
            $registerService = new \App\Application\Services\RegisterService(new UserRepository(),$validationHandler);
            try
            {
                if (($user = $registerService->execute($login, $mail, $password, $checkPassword)) !== null)
                {
                   $this->render('dashboard/register.html.twig',[
                       'addedNewUser' => "Dodano użytkownika $login"
                       ]);
                   exit;
                }
                else
                {
                   $this->render('dashboard/register.html.twig', array(
                       'validationErros' => $validationHandler->getErrorMesages()
                       ));
                   exit;
                }   
            } 
            catch (RegisterLogicException $ex) 
            {       
                $this->render('dashboard/register.html.twig', array(
                       'validationErros' => [$ex->getMessage()]
                       ));
                exit;
            }
        }
        $this->render('dashboard/register.html.twig');
    }
    
    public function addResults()
    {
        if ($this->getUser() == null)
        {
            header('Location: /home');
            exit;
        }
        $this->render('dashboard/addResults.html.twig');
        
    }
    
    public function logout()
    {
       session_destroy();
       header('Location: /home/login'); 
       exit;
    }        
}
