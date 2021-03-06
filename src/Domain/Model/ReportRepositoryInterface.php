<?php
declare(strict_types=1);

namespace App\Domain\Model;

interface ReportRepositoryInterface {
    public function allUsers();
    public function resultsForUsers();
}
