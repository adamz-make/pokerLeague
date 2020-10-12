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

    public function prepareData(UserRepositoryInterface $userRepo, ResultRepositoryInterface $resultRepo, MatchRepositoryInterface $matchRepo) 
    {
        $matches = $matchRepo->getAllMatches();
        $results = $resultRepo->getAllResults();
        foreach($this->filters->getUsers() as $userName)
        {
            $users[] = $userRepo->getByLogin($userName); //users są już tutaj pobrani
        }
        $filtersArray = ['dateFrom' => $this->filters->getDateFrom(), 'dateTo' => $this->filters->getDateTo()];
        $domainReportSummaryDataCreatorService = new DomainReportSummaryDataCreatorService();
        $this->data = $domainReportSummaryDataCreatorService->Execute($matches, $users , $results, $filtersArray); 
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
        return $data;
    }

}
