<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\Result;

interface ResultRepositoryInterface {
    public function add(Result $result);
    public function isResultForUserAdded(Result $result);
    public function getResultsForRanking();
    public function getAllResults();
}
