<?php
declare(strict_type=1);

namespace App\Infrastructure\Services;

use App\Domain\Model\ReportExporterInterface;

class ReportSummaryExporterService implements ReportExporterInterface{
    
    public function exportToExcel($data): array 
    {
        //użyć php spreadsheet. Spreadsheet (odpwoiednia metoda) moze dane zapisać do Excela, albo wyrzucić w Htmlu
        return ['sciezka do pliku razem z nazwa pliku', 'nazwa pliku wyrzucana do przegladarki'];
    }

    /**
     * 
     * @return string zwrócić gotowy raport do wyświetlenie
     */
    public function exportToHtml($data): string  //obiekt który ma informację jacy są użytkownicy, mecze rezultaty itd.
    {
        return "Raport Podsumowania Html :$data";
    }
}
