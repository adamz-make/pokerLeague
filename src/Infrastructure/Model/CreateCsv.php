<?php
declare (strict_types=1);

namespace App\Infrastructure\Model;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Domain\Model\UserRepositoryInterface;

class CreateCsv {
    
    private $userRepo;
    private $reportName;

    public function __construct(UserRepositoryInterface $userRepo, $reportName)
    {
        $this->userRepo = $userRepo;
        $this->reportName = $reportName;
    }
    public function createReportForAllUsers()
    {
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $data = $this->userRepo->getAllUsers();;
        $i = 1;
        foreach ($data as $row)
        {                
            $j = 1;
            ($row->getLogin());
           
            $array = $row->jsonSerialize();
            foreach (array_keys($array) as $key)
            {  
                if ($i === 1)
                {
                    $sheet->setCellValueByColumnAndRow($j, $i, $key);
                }
                else
                {
                  $sheet->setCellValueByColumnAndRow($j, $i, $array[$key]);  
                }
                
                $j+=1;
            }
            $i+=1;
        }
        $this->saveFileToXlsx($spreadSheet);
    }
    
    private function saveFileToXlsx($spreadSheet)
    {
        $writer = new Xlsx($spreadSheet);
        $writer->save($this->reportName.'.xlsx');
    }

    
}
