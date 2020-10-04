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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Infrastructure\Model\MatchRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Services\AddResultService;
use App\Infrastructure\Model\ResultRepository;
use App\Domain\Model\Match;


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
    /*
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
    }*/
    
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
                /*
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
                 
        */
    }
    
    
   /**
    * @Route(path="/home/loggedin", name="loggedin")
    */ 
    public function loggedin()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
//        if ($this->getUser() == null)
//        {
//            header('Location: /home/login');
//            exit;
//        }
        return $this->render('dashboard/loggedin.html.twig');
    }
    
    /**
    * @Route(path="/home/logout", name="logout")
    */
    public function logout()
    {
        
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
    
    /**
     * @Route (path="/home/addResults", name="addResults")
     */
    
    public function addResults(Request $request, MatchRepository $matchRepository, UserRepository $userRepository)
    {
        if ($this->getUser() == null)
        {
            header('Location: /home');
            exit;
        }
        $match = null;
        $lastMatch = $matchRepository->getLastMatch();
        $users = $userRepository->getAllUsers();
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $parameters = $request->request->all();
            $nrMatch = $parameters['matchNr']; 
            $match = $matchRepository->getMatchByNr($nrMatch);
            $userLogin = $parameters['user'];
            $user = $userRepository->getByLogin($userLogin);
            if ($match===null)
            {
                $match = new Match(null,$lastMatch->getMatchNr() + 1, date("Y-m-d H:i:s"));
                $matchRepository->add($match);
            }
            $addResultService = new AddResultService($user, $match, $parameters);
            $addResultService->execute(new ResultRepository());
                                       
        }
       return $this->render('dashboard/addResults.html.twig', [
           'match' => $match,
           'lastMatch' => $lastMatch,
           'users' => $users   
       ]);
        
    }    
}
