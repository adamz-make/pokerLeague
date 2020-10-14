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

class ReportSummaryExporterService implements ReportExporterInterface{
    
    public function exportToExcel($data): array 
    {

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Summary Report');
        $headers = $this->getHeaders($data['Users']);// pobranie nagłówkow do tabeli
        $this->setHeaders($headers, $spreadSheet); // wstawienie nagłówków w tabeli
        $matchesNumberList =  $this->getMatchNumbers($data['Matches']);// pobranie listy nr meczy
        $this->setMatchNumbersList($matchesNumberList, $spreadSheet); // wpisanie listy nr meczy jeden pod drugim
        $this->setDataForUserAndMatchNr($data,$spreadSheet); // dane dla danego użytkownika w danym meczu
        $this->doSummary($spreadSheet, $data);   //podsumowanie dla każdego gracza w ilu meczach brał udział i jaki ma bilans
        $writer = new Xlsx($spreadSheet);
        $writer->save('report.xlsx');
        return [getcwd() . '\report.xlsx','report.xlsx'];
    }

    /**
     * 
     * @return string zwrócić gotowy raport do wyświetlenie
     */
    public function exportToHtml($data): string  //obiekt który ma informację jacy są użytkownicy, mecze rezultaty itd.
    {
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

    private function doSummary(Spreadsheet &$spreadSheet, $data)
    {
        $row = $spreadSheet->getActiveSheet()->getHighestRow() + 3;
        $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Podsumowanie:');
        $row += 1;
        $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(2, $row, 'Gracze:');
        $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(3, $row, 'LiczbaMeczy:');
        $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(4, $row, 'LiczbaPiw:');
        $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(5, $row, 'LiczbaZetonow:');
        $row +=1;
        $rowForUsers = $row;

        foreach ($data['Users'] as $user)
        {
            $countMatchesForUser = 0;
            $countBeersForUser = 0;
            $countTokensForUser = 0;
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(2, $rowForUsers, $user->getLogin());
            foreach ($data['Results'] as $result)
            {
                if ($user->getId() === $result->getUserId())
                {
                    $countMatchesForUser += 1;
                    $countBeersForUser += $result->getBeers();
                    $countTokensForUser += $result->getTokens();
                    
                }
            }
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(3, $rowForUsers, $countMatchesForUser);
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(4, $rowForUsers, $countBeersForUser);
            $spreadSheet->getActiveSheet()->setCellValueByColumnAndRow(5, $rowForUsers, $countTokensForUser);
            $rowForUsers += 1;
        }
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
        
    
    
    private function getBeersFromResult(Result $result)
    {
        return $result->getBeers();
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
            $c +=1;
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
