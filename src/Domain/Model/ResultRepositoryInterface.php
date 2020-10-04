<?php

namespace App\Domain\Model;

use App\Domain\Model\Result;

interface ResultRepositoryInterface {
    public function add(Result $result);
}
