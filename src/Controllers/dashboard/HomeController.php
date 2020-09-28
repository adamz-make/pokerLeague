<?php



namespace App\Controllers\dashboard;

use App\Models\User;
use App\Models\UserRepository;


class HomeController extends \App\Controllers\AbstractController{

    public function index()
    {
        //Przekazanie zmiennej do widoku
        /*$this->render('dashboard/index.html.twig',array(
            'zmienna' => 'przekazalem',
        ));
         */
        $this->render('dashboard/index.html.twig');
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $login = $_POST['login'];
            $password = $_POST['pass'];
            if (($user = $this->validate($login, $password)) != null)
            {
                $_SESSION['user']=json_encode($user);
                header('Location: /dashboard/home/loggedin'); 
                exit;
            }              
        }
        $this->render('dashboard/login.html.twig', array(
            'notLoggedIn' => 'Niepoprawne login lub hasło. Spróbuj ponownie'
        ));
    }
    
    public function loggedin()
    {
        if ($this->getUser() == null)
        {
            header('Location: /dashboard/home');
            exit;
        }
        $this->render('dashboard/loggedin.html.twig');
    }
    
    public function registerNewUser()
    {
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $login = $_POST['login'];
            $password = $_POST['pass'];
            $checkPassword = $_POST['pass2'];
            $mail = $_POST['mail'];
            if ($this->checkMail($mail) === true)
            {
                if($password !== $checkPassword)
                {
                    $this->render('dashboard/register.html.twig', array(
                    'registerNewUser' => 'Hasła się nie zgadzają'
                    ));
                exit;
                }
            }
            else
            {
                $this->render('dashboard/register.html.twig', array(
                    'registerNewUser' => 'Niepoprawny mail'
                    ));
                exit;
            }     
            $userRepo = new UserRepository();
            if ($userRepo->getBy('mail', $mail) === null && $userRepo->getBy('login', $mail)=== null)
            {
                $user = new User($login, $password, $mail);
                if ($userRepo->registerNewUser($user) === true)
                {
                    $this->render('dashboard/register.html.twig', array(
                        'registerNewUser' => "Zarejestrowano nowego użytkownika o loginie $login"
                    ));
                    exit;
                }
                else
                {
                    $this->render('dashboard/register.html.twig', array(
                        'registerNewUser' => "Nie udało się zarejestrować nowego użytkownika"
                    ));
                    exit;
                }
                
            }
            $this->render('dashboard/register.html.twig', array(
                        'registerNewUser' => 'Już jest zarejestrowany użytkownik o podanym mailu lub loginie'
                    ));
                    exit;
        }
            
            
        
        $this->render('dashboard/register.html.twig');
    }
    
    public function addResults()
    {
        if ($this->getUser() == null)
        {
            header('Location: /dashboard/home');
            exit;
        }
        $this->render('dashboard/addResults.html.twig');
        
    }
    
    public function logout()
    {
       header('Location: /dashboard/home/login');
       session_destroy();
       exit;
    }
    
    private function validate($login, $password)
    {
        $userRepo = new UserRepository();
        $user = $userRepo->getBy('login',$login);
        if ($user instanceof User && $user->getPassword() == $password)
        {
            return $user;
        }
        return null;
    }
    
    private function checkMail($mail)
    {

        $pattern ='/^[a-zA-Z0-9\.\-_]+\@[a-zA-Z0-9\.\-_]+\.[a-z A-Z]+/'; 
        $result = preg_match($pattern,$mail);
        if ($result == 1)
        {
            return true; 
        }
        return false;
    }

    
}