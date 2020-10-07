<?php

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Model\MatchRepositoryInterface;
use App\Domain\Model\UserRepositoryInterface;
use App\Infrastructure\Model\MatchRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Application\Services\CheckIfMatchAddedForUserService;
use App\Infrastructure\Model\ResultRepository;
use App\Domain\Model\Match;
use App\Application\Services\AddResultService;
use App\Application\Services\CreateRankingService;

class ResultController extends AbstractController{

     /**
     * @Route(path="/home/addResults", name="addResults")
     */    
    public function addResults(Request $request,MatchRepositoryInterface $matchRepository, UserRepositoryInterface $userRepository)
    {
        if ($this->getUser() == null)
        {
            header('Location: /home');
            exit;
        }
        $match = null;
        $lastMatch = $matchRepository->getLastMatch();
        $users = $userRepository->getAllUsers();
        $resultForUserAdded = null;
        if ($_SERVER['REQUEST_METHOD']=='POST')
        {
            $parameters = $request->request->all();
            $nrMatch = $parameters['matchNr']; 
            $match = $matchRepository->getMatchByNr($nrMatch);
            $userLogin = $parameters['user'];
            $user = $userRepository->getByLogin($userLogin);
            if ($match === null)
            {
                if ($lastMatch !== null)
                {
                   $match = new Match(null,$lastMatch->getMatchNr() + 1, date("Y-m-d H:i:s"));  // to dodane jeżeli peirwszy mecz nie jest dodany
                }
                else
                {
                   $match = new Match(null, 1, date("Y-m-d H:i:s")); 
                }
                $matchRepository->add($match); //dodaje do bazy danych i automatycznie mam nadany nr ID
                $match = $matchRepository->getMatchByNr($nrMatch); // jeszcze raz pobieram, bo przekazuje bez ID - konstruktor tworzy z null  (dwie linie wyżej)
            }
            $addResultService = new AddResultService(new ResultRepository());
            try
            {
             $result = $addResultService->execute($user, $match, $parameters);   
            } 
            catch (ResultAddedForUserException $ex)
            {
                $resultForUserAdded = $ex->getMessage();
            }                                                  
        }
        return $this->render('dashboard/addResults.html.twig', [
           'match' => $match,
           'lastMatch' => $lastMatch,
           'users' => $users,
           'resultForUserAdded' => $resultForUserAdded
       ]);        
    }
    
     /**
     * @Route(path="/home/ranking", name="ranking")
     */
    public function ranking(CreateRankingService $createRankingService)
    {
        $ranking = $createRankingService->execute();
        return $this->render('dashboard/ranking.html.twig',[
            'ranking' => $ranking
        ]);
    }
    
     /**
     * @Route(path="/home/addResults/MatchAddedForUser", name="matchAddedForUser")
     */
    //żadąć userrepositoryInterface - ogólnie interfejsów
    public function MatchAddedForUser(Request $request, UserRepositoryInterface $userRepo)
    {
        if ($_SERVER['REQUEST_METHOD']=='GET')
        {
            $user = $userRepo->getByLogin($request->get('user'));
            $user->eraseCredentials();
            $matchRepo = new MatchRepository();
            $match = $matchRepo->getMatchByNr($request->get('matchNr'));
            if ($user === null || $match === null)
            {
                return new Response(json_encode([
                    'matchResult' => null
                ]));
            }
            $checkifMatchAddedForUserService = new CheckIfMatchAddedForUserService(new ResultRepository());
            if (($result =$checkifMatchAddedForUserService->execute($user, $match)) !== null)
            {
                $outData = [
                    'user' => $user,
                    'match' => $match,
                    'result' => $result
                ];                
                return new Response(json_encode($outData));  
            }
        }
        return new Response(json_encode([
            'matchResult' => null
        ]));
    }
}
