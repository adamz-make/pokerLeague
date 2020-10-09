<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report {
    
    private $spreadSheet;
    private $reportName;
    
    public function __construct(String $reportName, Spreadsheet $spreadSheet)
    {
        $this->spreadSheet = $spreadSheet;
        $this->reportName = $reportName;
    }

    public function saveFileToXlsx()
    {
        $writer = new Xlsx($this->spreadSheet);
        $writer->save($this->reportName.'.xlsx');
    }
}
