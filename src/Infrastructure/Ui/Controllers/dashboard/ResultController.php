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
use App\Domain\Model\User;
use App\Application\Services\GetMatchPlayersService;
use App\Infrastructure\Model\UserRepository;

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
        $resultForUserAdded = null;
        $match = null;
        $lastMatch = $matchRepository->getLastMatch();
        $users = $userRepository->getAllUsers();
       //array_unshift($users, new User (null,"Wybierz Gracza", null, null));
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $parameters = $request->request->all();
            $nrMatch = $parameters['matchNr']; 
            $match = $matchRepository->getMatchByNr($nrMatch);
            //$userLogin = $parameters['user'];
            //$user = $userRepository->getByLogin($userLogin);
            $getMatchPlayersService = new GetMatchPlayersService(new UserRepository());
            $matchPlayers = $getMatchPlayersService->execute($parameters);            
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
            foreach ($matchPlayers as $matchPlayer)
            {
                $match->addMatchPlayer($matchPlayer);
            }
            
            $addResultService = new AddResultService(new ResultRepository());
            try
            {
                $addResultService->execute($match);  
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
           'resultForUserAdded' => $resultForUserAdded,
           'usersCount' => count($users)
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
    public function MatchAddedForUser(Request $request)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $matchRepo = new MatchRepository();
            $match = $matchRepo->getMatchByNr($request->get('matchNr'));
            if (!empty ($match))
            {
                 foreach ($match->getMatchPlayers() as $matchPlayer)
                {
                    $matchPlayer->getUser()->eraseCredentials();
                }
            }
            if ($match === null)
            {
                return new Response(json_encode([
                    'matchResult' => null
                ]));
            }
            return new Response(json_encode(['matchResult' => $match]));
        }
        return new Response(json_encode([
            'matchResult' => null
        ]));
    }
}
