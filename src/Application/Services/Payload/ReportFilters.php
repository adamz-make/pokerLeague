<?php
declare (strict_types=1);

namespace App\Application\Payload;

class ReportFilters {
    private $dateFrom;
    private $dateTo;
    private $users = [];
            
    public function setDateFrom($dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }
    
    public function getDateFrom()
    {
        return $this->dateFrom;        
    }
    
    public function setDateTo($dateTo)
    {
        $this->dateTo = $dateTo;
    }
    
    public function getDateTo()
    {
        return $this->dateTo;
    }
    
    public function setUsers ($users)
    {
        $this->users = $users;
    }
    
    public function getUsers()
    {
        return $this->users;
    }
    //klasa będzie zawierała pola które będą filtrami dla raportu, np data od, data do, użytkownik itd
    // gettery i settery do ustawiania powyższych filtrów
}
