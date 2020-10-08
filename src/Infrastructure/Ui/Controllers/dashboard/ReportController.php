<?php
declare(strict_types=1);

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Infrastructure\Model\CreateCsv;
use App\Domain\Model\UserRepositoryInterface;

class ReportController extends AbstractController{
    
    /**
     *@Route (path="/home/rankingReport", name="rankingReport")
     */
    public function rankingReport(UserRepositoryInterface $userRepo)
    {
        return $this->render('dashboard/loggedin.html.twig');
    }
    
     /**
     *@Route (path="/home/rankingReport", name="rankingReport")
     */
    public function allUsersReport(UserRepositoryInterface $userRepo)
    {
        $reportName = 'Users';
        $createCsv = new CreateCsv($userRepo,$reportName);
        $createCsv->createReportForAllUsers();
        return $this->render('dashboard/loggedin.html.twig');
    }
}
