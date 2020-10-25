<?php

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Domain\Model\MatchRepositoryInterface;
use App\Domain\Model\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Application\Services\CheckIfMatchAddedForUserService;
use App\Infrastructure\Model\ResultRepository;
use App\Domain\Model\Match;
use App\Application\Services\AddResultService;
use App\Application\Services\CreateRankingService;
use App\Domain\Model\User;
use App\Application\Services\GetMatchPlayersService;
use App\Infrastructure\Model\UserRepository;
use App\Domain\Model\ResultRepositoryInterface;
use App\Application\Payload\FilteredPlayers;

class ResultController extends AbstractController{

     /**
     * @Route(path="/home/addResults", name="addResults")
     */    
    public function addResults(Request $request,MatchRepositoryInterface $matchRepository, UserRepositoryInterface $userRepository, ResultRepositoryInterface $resultRepo)
    {
        // podajemy tylko liczbę żetonów i na jej podstawie wyliczane są piwa oraz punkty, przy podawaniu meczu podawać informację ile punktów za ile żetonów, ile piw za ile za żetonow
        // W tym powyższym skorzystać z abstract factory i z strategy
        // bridge i decorator
        // Powyższr mam na skypie (12 październik) od Przemka
        
        
        //jak mecz na piwa to raczej nie nie na punkty i na odwórt (chociąz mogą być wyjątki) - wzorzec strategia. Jak mecz na piwa, to wyliczam ilość piw,
        // jak mecz na punkty to przeliczam na punkty. Jak zrobić w smsie mam od Przemka.
        
        //nie dodawaj wszystkich graczy na raz. Trzeba dodać jakieś pole wyboru żeby pokazały się pola do wynoru dla kolejnego gracza
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
            $filteredPlayers = new FilteredPlayers();
            $filteredPlayers->setUsers($parameters['users']);
            $getMatchPlayersService = new GetMatchPlayersService($userRepository);
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
            
            $addResultService = new AddResultService($resultRepo);
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
    public function MatchAddedForUser(Request $request, MatchRepositoryInterface $matchRepo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
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
