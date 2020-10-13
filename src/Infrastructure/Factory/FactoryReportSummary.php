<?php
declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Application\Services\ReportSummaryDataCreatorService;
use App\Infrastructure\Services\ReportSummaryExporterService;
use App\Domain\Model\ReportDataCreatorInterface;
use App\Domain\Model\ReportExporterInterface;
use App\Infrastructure\Model\UserRepository;
use App\Infrastructure\Model\MatchRepository;
use App\Infrastructure\Model\ResultRepository;

class FactoryReportSummary extends AbstractReportFactory {
    
    public function getDataCreator(): ReportDataCreatorInterface 
    {
        return new ReportSummaryDataCreatorService(new UserRepository(), new ResultRepository(), new MatchRepository());
    }

    public function getReportExporter(): ReportExporterInterface 
    {
        return new ReportSummaryExporterService();
    }

}
