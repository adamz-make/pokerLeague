<?php
declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Model\ReportRepositoryInterface;
use App\Domain\Services\Utils\CreateReportService as DomainCreateReportService;

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
        $domainCreateReportService = new DomainCreateReportService();
        return $domainCreateReportService->execute($data);
        
    }
    
}
