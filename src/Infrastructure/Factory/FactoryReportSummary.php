<?php
declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Application\Services\ReportSummaryDataCreatorService;
use App\Infrastructure\Services\ReportSummaryExporterService;
use App\Domain\Model\ReportDataCreatorInterface;
use App\Domain\Model\ReportExporterInterface;

class FactoryReportSummary extends AbstractReportFactory {
    
    public function getDataCreator(): ReportDataCreatorInterface 
    {
        return new ReportSummaryDataCreatorService();
    }

    public function getReportExporter(): ReportExporterInterface 
    {
        return new ReportSummaryExporterService();
    }

}
