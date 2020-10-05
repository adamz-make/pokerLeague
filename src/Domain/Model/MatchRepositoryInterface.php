<?php
DECLARE (strict_types=1);

namespace App\Domain\Model;

Interface MatchRepositoryInterface {
 public function getLastMatch();
 public function getMatchByNr($nr);
 public function add(Match $match);
    
 
}
