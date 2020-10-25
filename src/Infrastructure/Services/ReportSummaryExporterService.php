<?php
declare(strict_type=1);

namespace App\Infrastructure\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Domain\Model\ReportExporterInterface;
use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\Result;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Readxlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\IOFactory;


class ReportSummaryExporterService implements ReportExporterInterface{
    
    public function exportToExcel($data) 
    {       
        $spreadSheet = $this->prepareSpreadSheet($data);
        $writer = new Xlsx($spreadSheet);
        $writer->save('report.xlsx');
        return [getcwd() . '\report.xlsx', 'report.xlsx'];
       
    }
    /**
     * 
     * @return string zwrócić gotowy raport do wyświetlenie
     */
    public function exportToHtml($data): ?string  //obiekt który ma informację jacy są użytkownicy, mecze rezultaty itd.
    {
        $spreadSheet = $this->prepareSpreadSheet($data);
        $writer = IOFactory::createWriter($spreadSheet,'Html');
        $response = $writer->save('php://output');
        return $response;
    }
    
    private function prepareSpreadSheet($data)
    {
        $data = $data->getSummaryObjectReports();
        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Summary Report');
        //Dane
        $userColumnNr = $this->setUserNumberList($data);
        $sheet->setCellValueByColumnAndRow(1,3,'Nr meczu');
        foreach ($userColumnNr as $header => $column)
        {
            $sheet->setCellValueByColumnAndRow($column +1, 3, $header); // w 3 wierszu są nagłówki, a kolumna +1 bo od 2 kolumny wpisujemy
        }
        $matchNrList = $this->getMatchNrList($data);


        $matchRow = 4;
        foreach ($data as $object)
        {
            if (isset($matchNr))
            {
                if ($object->getNrMatch() !== $matchNr)
                {
                    $matchRow += 2;
                    $sheet->setCellValueByColumnAndRow(1, $matchRow, $object->getNrMatch());
                }
                $matchNr = $object->getNrMatch();
                $sheet->setCellValueByColumnAndRow(1, $matchRow + 1, 'Podsumowanie: ');
                $sheet->setCellValueByColumnAndRow($userColumnNr[$object->getUserName()] + 1, $matchRow,
                        $object->getBeers());//liczba piw
                $sheet->setCellValueByColumnAndRow($userColumnNr[$object->getUserName()] + 1, $matchRow +1, 
                        $object->getCumulatedBeers());
            }
            else
            {     
                $matchNr = $object->getNrMatch();
                $sheet->setCellValueByColumnAndRow(1, $matchRow, $object->getNrMatch());
                $sheet->setCellValueByColumnAndRow(1, $matchRow + 1, 'Podsumowanie: ');
                $sheet->setCellValueByColumnAndRow($userColumnNr[$object->getUserName()] + 1, $matchRow,
                        $object->getBeers());//liczba piw
                $sheet->setCellValueByColumnAndRow($userColumnNr[$object->getUserName()] + 1, $matchRow + 1, 
                        $object->getCumulatedBeers());
            }
            $lastRow = $matchRow; 
        }
          
        $this->doSummary($spreadSheet, $data, $lastRow);
        return $spreadSheet;
    }
    
    private function checkLimit()
    {
        return random_int(0, 100) > 50;
    }
    
    private function doSummary(Spreadsheet $spreadSheet, $data, $lastRow)
    {
        $sheet = $spreadSheet->getActiveSheet();
        $row = $lastRow +3;
        $sheet->setCellValueByColumnAndRow(1, $row, 'Podsumowanie:');
        $row += 1;
        $sheet->setCellValueByColumnAndRow(1, $row, 'Gracze:');
        $sheet->setCellValueByColumnAndRow(2, $row, 'Podsumowanie Piw');
        $sheet->setCellValueByColumnAndRow(3, $row, 'Podsumowanie Punktów');
        $sheet->setCellValueByColumnAndRow(4, $row, 'Podsumowanie Żetonów');
        
        $userNumberList = $this->setUserNumberList($data);
        foreach ($userNumberList as $user => $number)
        {
            $sheet->setCellValueByColumnAndRow(1, $row + $number, $user);
            $lastObjectForUser[$user] = $this->getLastObjectForUser($data, $user);
            $beersValueRange = "=" . $this->encodeNumberToColumn(1 + $number) . ($lastRow + 1) ;
            $sheet->setCellValueByColumnAndRow(2, $row + $number,$beersValueRange);
            $sheet->setCellValueByColumnAndRow(3, $row + $number, $lastObjectForUser[$user]->getCumulatedPoints());
            $sheet->setCellValueByColumnAndRow(4, $row + $number, $lastObjectForUser[$user]->getCumulatedTokens());
        }       
    }
    
    private function encodeNumberToColumn($number)
    {
        switch ($number)
        {
            case 1:
                return 'A';
            case 2:
                return 'B';
            case 3:
                return 'C';
            case 4:
                return 'D';
            case 5:
                return 'E';
            case 6:
                return 'F';
             default:
                return null;   
        }
    }
    private function getLastObjectForUser($data, $userName)
    {
        $lastObject = null;
        foreach ($data as $object)
        {
            if ($object->getUsername() === $userName)
            {
                $lastObject = $object;
            }
        }
        return $lastObject;
    }
    
    
    private function getMatchNrList($data): array
    {
        $rowMatchNr = [];
        $row = 4; // od 4 wiersza wpisujemy
        foreach ($data as $record)
        {
            if (empty($rowMatchNr[$record->getNrMatch()]))
            {
                $rowMatchNr[$record->getNrMatch()] = $row;
                $row += 2;
            }
        }
        return $rowMatchNr;   
    }
    
    private function  setUserNumberList($data): array
    {
        $userColumnNr = [];
        $number = 1; //dane dotyczące użytkowników wpisujemy od 2 kolumny
        foreach ($data as $record)
        {
            if (!key_exists($record->getUserName(), $userColumnNr))
            {
                $userColumnNr[$record->getUserName()] = $number;
                $number += 1;
            }
        }
        return $userColumnNr;
    }

    private function getBeersFromPreviousResultRow($row, $column, Spreadsheet $spreadSheet)
    {
        if (is_numeric($spreadSheet->getActiveSheet()->getCellByColumnAndRow($column, $row - 1)->getValue()))
        {
            return $spreadSheet->getActiveSheet()->getCellByColumnAndRow($column, $row - 1)->getValue();
        }
        return 0;
        
    }
    
    private function getMatchNumbers ($matches)
    {
        $matchesNrList = null;
        foreach ($matches as $match)
        {
            $matchesNrList[] = $match->getMatchNr();
        }
        return $matchesNrList;
    }
    
    private function getUserColumnNrFromExcel($resultUser, Spreadsheet $spreadSheet)
    {
        $r = 4; //dane odnośnie użytkownikow są w 4 wierszu
        $c = 2; // dane zaczynają się od drugiej kolumnny
         do 
         {
            $userName = $spreadSheet->getActiveSheet()->getCellByColumnAndRow($c, $r)->getValue();
            if ($userName === $resultUser)
            {
                return $c;
            }
            $c +=1;
         } while ($spreadSheet->getActiveSheet()->getCellByColumnAndRow($c,$r)->getValue() !== '');
         return $c;
    }
    
    private function getUserNameForResult($result, $users)
    {
        $userId = $result->getUserId();
        foreach ($users as $user)
        {
            if ($user->getId() === $userId)
            {
                return  $user->getLogin();
            }
        }
        return null;
    }
    
    private function getMatchRowNrFromExcel($resultNrMatch, Spreadsheet $spreadSheet)
    {
        $r = 5; //dane odnośnie nr meczu są od 5 wiersza
        $c = 1; //dane są w 1 kolumnie
        $highestRow = $spreadSheet->getActiveSheet()->getHighestRow();
        for ($r = 5; $r <= $highestRow; $r++)
        {
            $nrMatch = $spreadSheet->getActiveSheet()->getCellByColumnAndRow($c, $r)->getValue();
             if ($nrMatch == $resultNrMatch)
            {
                return $r;
            }
        }
        return null;
    }
    
    private function getNrMatchFromResult(Result $result, $matches)
    {
        $resultMatchId = $result->getMatchId();
        $nrMatch = null;
        foreach ($matches as $match)
        {
            if ($match->getMatchId() === $resultMatchId)
            {
                return $nrMatch = $match->getMatchNr();
            }
        }
        return $nrMatch;
    }
    
    private function getHeaders($users)
    {
        $headers = ['Nr meczu'];
        foreach ($users as $user)
        {
            $headers[] = $user->getLogin();
        }         
        return $headers;
    }
    
    private function setHeaders($headers, Spreadsheet &$spreadSheet)
    {
        $r = 4;
        $c = 1;
        foreach ($headers as $header)
        {
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow($c, $r,$header);
            $c += 1;
        }            
    }
    
    private function setMatchNumbersList($matchNumbers, Spreadsheet &$spreadSheet)
    {
        $r = 5;
        $c = 1;
        foreach ($matchNumbers as $matchNr)
        {
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow($c, $r, $matchNr);
            $r += 1;
        }
    }
    
    private function setDataForUserAndMatchNr($data, Spreadsheet &$spreadSheet)
    {
        $results = $data['Results'];
        foreach($results as $result)
        {
            $resultNrMatch = $this->getNrMatchFromResult($result,$data['Matches']);
            $rowCoordinator = $this->getMatchRowNrFromExcel($resultNrMatch, $spreadSheet);
            $resultUser = $this->getUserNameForResult($result, $data['Users']);
            $columnCoordinator = $this->getUserColumnNrFromExcel($resultUser, $spreadSheet);
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow($columnCoordinator,
                    $rowCoordinator, ($result->getBeers() + $this->getBeersFromPreviousResultRow($rowCoordinator,$columnCoordinator, $spreadSheet)));
        }
    }
}
