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
use App\Application\Services\Utils\ResultAddedForUserException;
use App\Application\Services\CreateRankingService;
use App\Application\Services\CheckIfMatchAddedForUserService;

//kontrolery porobić
class HomeController extends AbstractController{

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
        return $this->render('dashboard/loggedin.html.twig',[
            'report' => null
        ]);
    }
}
