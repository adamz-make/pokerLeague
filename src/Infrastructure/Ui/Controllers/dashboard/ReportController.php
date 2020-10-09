<?php
declare(strict_types=1);

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Model\CreateReport;
use App\Domain\Model\UserRepositoryInterface;
use App\Domain\Model\ReportRepositoryInterface;
use App\Infrastructure\Model\Report;
use App\Application\Services\CreateReportService;

class ReportController extends AbstractController{
    
    /**
     *@Route (path="/home/rankingReport", name="rankingReport")
     */
    public function rankingReport(UserRepositoryInterface $userRepo)
    {
        return $this->render('dashboard/loggedin.html.twig');
    }
    
     /**
     *@Route (path="/home/usersReport", name="usersReport")
     */
    public function allUsersReport(ReportRepositoryInterface $reportRepo)
    {
        $createReportService = new CreateReportService($reportRepo);
        $reportName = 'AllUsersReport';
        $spreadSheet = $createReportService->execute($reportName);    
        $report = new Report ($reportName, $spreadSheet);
        $report->saveFileToXlsx();
        return $this->render('dashboard/loggedin.html.twig');
    }
    
     /**
     *@Route (path="/home/resultsForAllUsers", name="usersReport")
     */
    public function resultsForAllUsers(ReportRepositoryInterface $reportRepo)
    {
        $createReportService = new CreateReportService($reportRepo);
        $reportName = 'resultsForAllUsers';
        $spreadSheet = $createReportService->execute($reportName);    
        $report = new Report ($reportName, $spreadSheet);
        $report->saveFileToXlsx();
        return $this->render('dashboard/loggedin.html.twig');
    }
}
