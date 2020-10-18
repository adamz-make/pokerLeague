<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\ReportDataCreatorInterface;
use App\Application\Payload\ReportFilters;
use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Model\ResultRepositoryInterface;
use App\Domain\Model\MatchRepositoryInterface;
use App\Domain\Services\ReportSummaryDataCreatorService as DomainReportSummaryDataCreatorService;


class ReportSummaryDataCreatorService implements ReportDataCreatorInterface 
{

    /**
     *
     * @var ReportFilters
     */
    private $filters;
    private $data;
    private $userRepo;
    private $resultRepo;
    private $matchRepo;
    
    public function __construct(UserRepositoryInterface $userRepo, ResultRepositoryInterface $resultRepo, MatchRepositoryInterface $matchRepo)
    {
        $this->userRepo = $userRepo;
        $this->matchRepo = $matchRepo;
        $this->resultRepo = $resultRepo;
    }

    public function prepareData() 
    {
        $matches = $this->matchRepo->getMatchesByDate($this->filters->getDateFrom(), $this->filters->getDateTo());
        //rezultaty nie są mi potrzebne, bo wyciągam mecze a jak mecze przeszłły na domene to tam są rezultaty
        //rezultaty też lepiej by było wyciągnąć z SQl, na podstawie nr meczy i użytkowników
        //$results = $this->resultRepo->getAllResults();
        if (empty($this->filters->getUsers()))
        {
            $users = $this->userRepo->getAllUsers();
        }
        else
        {
            foreach ($this->filters->getUsers() as $user)
            {
                $users[] = $this->userRepo->getByLogin($user);
            }
        }
        //$filtersArray = ['dateFrom' => $this->filters->getDateFrom(), 'dateTo' => $this->filters->getDateTo(), 'users' => $this->filters->getUsers()];
        $domainReportSummaryDataCreatorService = new DomainReportSummaryDataCreatorService();
        $this->data = $domainReportSummaryDataCreatorService->execute($matches, $users); 
    }
    
    /**
     * 
     * @param ReportFilters $reportFilters
     */
    public function setFilters(ReportFilters $reportFilters) 
    {
        $this->filters = $reportFilters;
    }

    public function getData() 
    {
        return $this->data;
    }

}
