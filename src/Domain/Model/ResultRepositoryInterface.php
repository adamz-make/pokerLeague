<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\Result;

interface ResultRepositoryInterface {
    public function getResultsForRanking();
    public function getAllResults();
    public function persist(Result $result);
}
