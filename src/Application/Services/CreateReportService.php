<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\ReportRepositoryInterface;
use App\Domain\Services\CreateReportService as DomainCreateReportService;
use App\Application\Services\Utils\NoReportException;

class CreateReportService {

    private $reportRepo;

    public function __construct(ReportRepositoryInterface $reportRepo )
    {
       $this->reportRepo = $reportRepo;
    }
    
    public function execute(string $reportName)
    {
        if ($reportName === 'AllUsersReport')
        {
            $data = $this->reportRepo->allUsers();
        }
        if ($reportName === 'ResultsForAllUser')
        {
            $data = $this->reportRepo->resultsForUsers();
        }
        if (empty($data))
        {
            throw new NoReportException("Raport o nazwie $reportName nie istnieje");
        }
        $domainCreateReportService = new DomainCreateReportService();
        return $domainCreateReportService->execute($data);
        
    }
    
}
