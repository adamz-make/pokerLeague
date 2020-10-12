<?php
declare(strict_type=1);

namespace App\Infrastructure\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Domain\Model\ReportExporterInterface;
use App\Domain\Model\User;
use App\Domain\Model\Match;
use App\Domain\Model\Result;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportSummaryExporterService implements ReportExporterInterface{
    
    public function exportToExcel($data): array 
    {
        $spreadSheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->mergeCells('A1:C1');
        $sheet->setCellValue('A1', 'Summary Report');
        
        $headers = $this->setHeaders($data['Users']);
        $r = 4;
        $c = 1;
        //nagłówki raportu
        foreach ($headers as $header)
        {
            $sheet->setCellValueByColumnAndRow($c, $r, $header);
            $c += 1;
        }
        $r = 5;
        $c = 1;
        //numery meczów
        $matchNrList = $this->getMatchNumbers($data['Matches']);
        foreach ($matchNrList as $matchNr)
        {
            $sheet->setCellValueByColumnAndRow($c, $r, $header);
            $r += 1;
        }
        //Liczba piw po każdym meczu dla każdego gracza
        $results = $data['Results'];
        foreach($results as $result)
        {
            $resultNrMatch = $this->getNrMatchFromResult($result,$data['Matches']);
            $rowCoordinator = $this->getRowNrFromExcel($resultNrMatch, $spreadSheet);
            $resultUser = $this->getUserNameForResult($result, $data['Users']);
            $columnCoordinator = $this->getColumnNrFromExcel ($resultUser, $spreadSheet);
            $sheet->setCellValueByColumnAndRow($columnCoordinator, $rowCoordinator, $this->getBeersFromResult($result));
        }
        
        
        $writer = new Xlsx($spreadSheet);
        $writer->save('report');
        //użyć php spreadsheet. Spreadsheet (odpwoiednia metoda) moze dane zapisać do Excela, albo wyrzucić w Htmlu
        //return ['sciezka do pliku razem z nazwa pliku', 'nazwa pliku wyrzucana do przegladarki'];
    }

    /**
     * 
     * @return string zwrócić gotowy raport do wyświetlenie
     */
    public function exportToHtml($data): string  //obiekt który ma informację jacy są użytkownicy, mecze rezultaty itd.
    {
        return "Raport Podsumowania Html :$data";
    }
    
    private function getBeersFromResult(Result $result)
    {
        return $result->getBeers();
    }
    
    private function getColumnNrFromExcel($resultUser, Spreadsheet $spreadSheet)
    {
        $r = 4; //dane odnośnie użytkownikow są w 4 wierszu
        $c = 2; // dane zaczynają się od drugiej kolumnny
         do 
         {
            $userName = $spreadSheet->getActiveSheet()->getCellByColumnAndRow($c, $r);
            if ($userName === $resultUser)
            {
                return $c;
            }
            $c +=1;
         } while ($spreadSheet->getActiveSheet->getCellByColumnAndRow($c,$r) !== '');
         return $c;
    }
    
    private function getUserNameForResult(Result $result, User $users)
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
    
    private function getRowNrFromExcel($resultNrMatch, Spreadsheet $spreadSheet)
    {
        $r = 5; //dane odnośnie nr meczu są od 5 wiersza
        $c = 1; //dane są w 1 kolumnie
         do 
         {
            $nrMatch = $spreadSheet->getActiveSheet()->getCellByColumnAndRow($c, $r);
            if ($nrMatch === $resultNrMatch)
            {
                return $r;
            }
            $r +=1;
         } while ($spreadSheet->getActiveSheet->getCellByColumnAndRow($c,$r) !== '');
         return $r;
    }
    
    private function getNrMatchFromResult(Result $result, Match $matches)
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
    
    private function setHeaders(User $users)
    {
        $headers = ['Nr meczu'];
        foreach ($users as $user)
        {
            $headers[] = $user->getLogin();
        }            
        return $headers;        
    }
}
