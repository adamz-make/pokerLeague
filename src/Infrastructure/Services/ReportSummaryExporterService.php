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

class ReportSummaryExporterService implements ReportExporterInterface{
    
    public function exportToExcel($data, $toHtml = false) 
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
        foreach ($matchNrList as $matchNr => $row)
        {
           $sheet->setCellValueByColumnAndRow(1, $row, $matchNr);
           $sheet->setCellValueByColumnAndRow(1, $row + 1, "Podsumowanie rozegranych kolejek");  
        }
        //Spróbować tak napisać, że gdybyśmy chcieli żeby wpadał dodatkowy wiersz to żeby to można było łatwo przerobić
        $nrMatch ='';
       // $r = 4;
        $lastRow = 0;
        foreach ($data as $record)
        {
            if ($nrMatch === '' || $nrMatch === $record->getNrMatch())
            {
                $nrMatch = $record->getNrMatch();
                $sheet->setCellValueByColumnAndRow($userColumnNr[$record->getUserName()] + 1, $matchNrList[$record->getNrMatch()],
                        $record->getBeers());//liczba piw
                $sheet->setCellValueByColumnAndRow($userColumnNr[$record->getUserName()] + 1, $matchNrList[$record->getNrMatch()] + 1,
                        $record->getCumulatedBeers());//skumulowane piwa
                if (checkLimit)
                {
                    
                }
            }
            else
            {
                $nrMatch = $record->getNrMatch();
               // $r +=2;  
                $sheet->setCellValueByColumnAndRow($userColumnNr[$record->getUserName()] + 1, $matchNrList[$record->getNrMatch()],
                        $record->getBeers());//liczba piw
                $sheet->setCellValueByColumnAndRow($userColumnNr[$record->getUserName()] + 1, $matchNrList[$record->getNrMatch()] + 1, 
                        $record->getCumulatedBeers());//skumulowane piwa
            }
            $lastRow = $matchNrList[$record->getNrMatch()];
        }
        $this->doSummary($spreadSheet, $data, $lastRow);
        $writer = new Xlsx($spreadSheet);
        if ($toHtml === false)
        {    
            $writer->save('report.xlsx');
            return [getcwd() . '\report.xlsx', 'report.xlsx'];
        }
        else
        {
            $response =  new StreamedResponse(
                function () use ($writer) {
                    $writer->save('php://output');
                }
            );
            $response->headers->set('Content-Type', 'application/vnd.ms-excel');
            $response->headers->set('Content-Disposition', 'attachment;filename="ExportScan.xls"');
            $response->headers->set('Cache-Control','max-age=0');
            return $response;
        }        
    }
    /**
     * 
     * @return string zwrócić gotowy raport do wyświetlenie
     */
    public function exportToHtml($data): string  //obiekt który ma informację jacy są użytkownicy, mecze rezultaty itd.
    {
        return $this->exportToExcel($data, true);
        
        
        
        $stringToHtml = '<table border="6" >';
        $headers = $this->getHeaders($data['Users']);
        $stringToHtml .= '<tr>'; //dodanie wiersza na nagłówki
        foreach ($headers as $header)
        {
            $stringToHtml .= "<td>$header</td>";
        }      
        $stringToHtml .= '</tr>';
        $matchesNumberList = $this->getMatchNumbers($data['Matches']);

        foreach ($matchesNumberList as $matchNumber)
        {
            $stringToHtml .= "<tr><td>$matchNumber</td>"; 
            foreach ($data ['Matches'] as $match)
            {
                if ($matchNumber === $match->getMatchNr())
                {
                    $matchId = $match->getMatchId();
                    foreach($data['Users'] as $user)
                    {
                        $userId = $user->getId();
                        if (!isset($sumBeers[$user->getLogin()]))
                        {
                          $sumBeers[$user->getLogin()] = 0;  
                        }
                        $userPlayedMatch = false;
                        foreach ($data['Results'] as $result)
                        {
                            if ($result->getMatchId() === $matchId and $result->getUserId() === $userId)
                            {
                                $sumBeers[$user->getLogin()] +=  $result->getBeers();
                                $stringToHtml .= '<td>' . $sumBeers[$user->getLogin()] . '</td>';
                                $userPlayedMatch = true;
                            }
                        }
                        if ($userPlayedMatch === false)
                        {
                            // .= '<td>' . $sumBeers[$user->getLogin()] . '</td>'; To nie potrzebne bo w Excelu tego też nie ma tam gdzie nie było rozgrywanego meczu, jest puste pole
                        }
                    }
                }
            } 
        }
        $stringToHtml .= '</table><p>Podsumowanie</p><table border="6">';
        $stringToHtml .='<tr><td>Gracze:</td><td>Liczba Rozegranych meczy:</td><td>Bilans Piw:</td><td>Bilans Żetonów</td></tr>';
        foreach ($data['Users'] as $user)
        {
            $stringToHtml .= '<tr><td>' . $user->getLogin() . '</td>';
            $playedMatchesByUser = 0;
            $summaryBeersForUser = 0; 
            $summaryTokensForUser = 0;   
            foreach ($data['Results'] as $result)
            { 
                if ($result->getUserId() === $user->getId())
                {
                    $playedMatchesByUser += 1;
                    $summaryBeersForUser += $result->getBeers();
                    $summaryTokensForUser += $result->getTokens();
                }      
            }
                $stringToHtml .= "<td>$playedMatchesByUser</td><td>$summaryBeersForUser</td><td>$summaryTokensForUser</td></tr>"; 
        }
        $stringToHtml .= '</table>';     
        return $stringToHtml;
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
            $sheet->setCellValueByColumnAndRow(2, $row + $number, $lastObjectForUser[$user]->getCumulatedBeers()); // zamień to żeby z excela pobierał
            $sheet->setCellValueByColumnAndRow(3, $row + $number, $lastObjectForUser[$user]->getCumulatedPoints());
            $sheet->setCellValueByColumnAndRow(4, $row + $number, $lastObjectForUser[$user]->getCumulatedTokens());
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
