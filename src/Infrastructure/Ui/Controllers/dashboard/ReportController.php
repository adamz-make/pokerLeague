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
use Symfony\Component\HttpFoundation\Request;
use App\Application\Services\Utils\NoReportException;
use App\Infrastructure\Factory\AbstractReportFactory;
use App\Application\Payload\ReportFilters;
use Symfony\Component\HttpFoundation\Response;
use App\Infrastructure\Model\UserRepository;
use App\Infrastructure\Model\MatchRepository;
use App\Infrastructure\Model\ResultRepository;
use App\Domain\Services\Utils\NotCorrrectFiltersException;

class ReportController extends AbstractController{
        
     /**
     *@Route (path="/home/Reports", name="Reports")
     */
    public function reports(Request $request,ReportRepositoryInterface $reportRepo)
    {
        $reportName ="";
        if ($_SERVER['REQUEST_METHOD']=='GET')
        {
            $reportName = ($request->query->get('report'));                
            $createReportService = new CreateReportService($reportRepo);
            try
            {
                $spreadSheet = $createReportService->execute($reportName);
                $report = new Report ($reportName, $spreadSheet);
                $report->saveFileToXlsx();
                return $this->render('dashboard/loggedin.html.twig',[
                    'report' => null
                ]);  
            } catch (NoReportException $ex) 
            {
                return $this->render('dashboard/loggedin.html.twig',[
                    'report' => $ex->getMessage()
                ]);  
            }
        }
        return $this->render('dashboard/loggedin.html.twig',[
            'report' => null
        ]); 
    }
    /**
     * @Route (path="/reports/getReports/{reportName}/{reportOutput}", name ="getReports", methods = {"GET"})
     */
    public function getReports(Request $request, string $reportName, string $reportOutput)
    {
        $factory = AbstractReportFactory::getFactory($reportName);
        $dataCreator = $factory->getDataCreator();
        $reportFilters = new ReportFilters();
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            $reportFilters->setDateFrom($request->query->get('dateFrom'));
            $reportFilters->setDateTo($request->query->get('dateTo'));
            $reportFilters->setUsers($request->query->get('users'));  
        }
        
        $dataCreator->setFilters($reportFilters);
        try
        {
            $dataCreator->prepareData(new UserRepository(), new ResultRepository(), new MatchRepository());
            $reportExporter = $factory->getReportExporter();
            if ($reportOutput == 'html')
            {
                return new Response($reportExporter->exportToHtml($dataCreator->getData()));
            }
            elseif ($reportOutput == 'excel')
            {
                list($path, $fileName) = $reportExporter->exportToExcel($dataCreator->getData());
                return $this->file($path, $fileName);
            }
            return new Response('Niepoprawne parametry wywołania', 400);
        } 
        catch (NotCorrrectFiltersException $ex)
        {
            return new Response($ex->getMessage(), 400);
        }
        
    }
}
