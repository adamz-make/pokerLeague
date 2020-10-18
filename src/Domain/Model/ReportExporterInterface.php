<?php
declare(strict_types=1);

namespace App\Domain\Model;

interface ReportExporterInterface {
    
    public function exportToHtml($data): string;
    public function exportToExcel($data, $toHtml = false);
}
