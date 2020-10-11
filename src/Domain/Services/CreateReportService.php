<?php
declare(strict_types=1);

namespace App\Domain\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

class CreateReportService {

    public function execute($data)
    {
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();     
        $i = 1;
        foreach ($data as $row)
        { 
            $j = 1;
            foreach (array_keys($row) as $key)
            {  
                $sheet->setCellValueByColumnAndRow($j, $i, $row[$key]);   
                $j+=1;
            }
            $i+=1;
        }
        
        return $spreadSheet;
    }
}
