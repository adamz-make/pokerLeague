<?php



namespace App\Controllers\dashboard;

use App\Models\User;
use App\Models\UserRepository;


class HomeController extends \App\Controllers\AbstractController{

    public function index()
    {
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
        $this->render('dashboard/login.html.twig');
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
    
    private function validate($login, $password)
    {
        $userRepo = new UserRepository();
        $user = $userRepo->getByLogin($login);
        if ($user instanceof User && $user->getPassword() == $password)
        {
            return $user;
        }
        return null;
    }

    
}
