<?php
declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Model\ReportDataCreatorInterface;
use App\Domain\Model\ReportExporterInterface;

abstract class AbstractReportFactory {

    const REPORT_SUMMARY = 'summary';
    const REPORT_USERS = 'users';
    
    final public static function getFactory(string $reportName)
    {
        switch ($reportName)
        {
            case self::REPORT_SUMMARY:
                return new FactoryReportSummary();
            case self::REPORT_USERS:
                break;
            default:
                throw new \RuntimeException("Brak raportu o nazwie $reportName");   
        }
    }
    /**
     * Zwraca obiekt, który przygotowuje dane do raportu
     * @return ReportDataCreatorInterface
     */
    abstract public function getDataCreator(): ReportDataCreatorInterface;
    /**
     * zwraca obiekt za pomocą którego exportujemy dane do HTML/Excle
     * @return ReportExporterInterface
     */
    abstract public function getReportExporter(): ReportExporterInterface;
            
}
