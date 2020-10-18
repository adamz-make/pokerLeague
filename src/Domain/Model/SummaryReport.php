<?php
declare (strict_types=1);

namespace App\Domain\Model;

class SummaryReport {
    
    private $summaryObjectReports = [];
    
    public function getSummaryObjectReports()
    {
        return $this->summaryObjectReports;
    }
    
    public function setSummaryObjectReports($summaryObjectReports)
    {
        $this->summaryObjectReports = $summaryObjectReports;
    }
}
