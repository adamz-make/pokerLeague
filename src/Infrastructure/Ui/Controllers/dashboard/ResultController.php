<?php

namespace App\Infrastructure\Ui\Controllers\dashboard;

use App\Application\Payload\AbstractRulesToMatch;
use App\Application\Payload\RulesToBeerMatch;
use App\Application\Payload\RulesToLeagueMatch;
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
use App\Application\Payload\ResultParameters;
use App\Infrastructure\Factory\MatchFactory\AbstractMatchFactory;

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
        // przy punktach (czyli np 1 - miejsce 50 pkt, 2 - 20 itd) to trzeba pamietać, że jak się aktualizuje dane to tak na prawdę, je trzeba by
        // dodać ponownie od 0. Bo gracz który miał 50 pkt po aktualizacji graczy może mieć 25 pkt (z 1 miejsca może spaść na 2)
        
        
        //Wybiorę jaki typ meczu, podam stawkę startową.
//W przypadku meczu ligowego zostanie wysłany formularz.
//I na podstawie wszystkich wyników każdy z uczestników otrzyma jakąś liczbę punktów.

//W przypadku meczu na piwa potrzeba podać ile każdy z graczy ma żetonów i je przeliczyć na piwa.
        if ($this->getUser() == null)
        {
            header('Location: /home');
            exit;
        }
        $resultForUserAdded = null;
        $match = null;
        $lastMatch = $matchRepository->getLastMatch();
        $users = $userRepository->getAllUsers();
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $parameters = $request->request->all();
            $nrMatch = $parameters['matchNr'];
            $factoryMatch = AbstractMatchFactory::getFactory($parameters['matchType']);
            $rulesToMatch = $factoryMatch->getRulesToMatch(); // tutaj będę miał ifnoramcję ile było żetonów na start, ale potrzebuje jeszcze ile wkupów było
            $rulesToMatch->setTokensOnStart($parameters['tokensOnStart']);
            $conversionRate = $rulesToMatch instanceof RulesToBeerMatch ? $parameters['countTokensToBeers']:null;
            $rulesToMatch->setConversionRate($conversionRate);
            $countTokensService = $factoryMatch->getTokensCountService();
            $match = $matchRepository->getMatchByNr($nrMatch);         
            $getMatchPlayersService = new GetMatchPlayersService($userRepository);
            $tokens = $parameters['tokens'];

            foreach (array_keys($parameters['users']) as $key)
            {// beers/ points będzie trzeba wyliczyć nie będzie podawane przez usera
                $pointsOrBeers = $countTokensService->execute($rulesToMatch, $users, $users[$key], $tokens[$key]);
                $matchPlayers[] = $getMatchPlayersService->execute($parameters['users'][$key], $rulesToMatch, $tokens[$key] , $pointsOrBeers);
            }

            if ($match === null)
            {
                if ($lastMatch !== null)
                {
                   $match = new Match(null,$lastMatch->getMatchNr() + 1, date("Y-m-d H:i:s"), $parameters['matchType']);  // to dodane jeżeli peirwszy mecz nie jest dodany
                }
                else
                {
                   $match = new Match(null, 1, date("Y-m-d H:i:s"), $parameters['matchType']);
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
     * @Route(path="/home/showAllResults", name="showAllResults")
     */
    public function showAllResults(MatchRepositoryInterface $matchRepository, UserRepositoryInterface $userRepository, ResultRepositoryInterface $resultRepo)
    {
        $matches = $matchRepository->getAllMatches();
        $users = $userRepository->getAllUsers();
        return $this->render('dashboard/showResults.html.twig',['matches' =>$matches,
                'users' => $users]);

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
