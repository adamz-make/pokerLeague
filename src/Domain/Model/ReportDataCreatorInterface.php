<?php
declare(strict_types=1);

namespace App\Domain\Model;
use App\Application\Payload\ReportFilters;

interface ReportDataCreatorInterface 
{    
    public function setFilters(ReportFilters $reportFilters);
    public function prepareData();
    public function getData();
}
