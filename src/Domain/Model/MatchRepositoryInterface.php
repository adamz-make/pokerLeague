<?php
DECLARE (strict_types=1);

namespace App\Domain\Model;

Interface MatchRepositoryInterface {
 public function getLastMatch(): ?Match;
 public function getMatchByNr($nr): ?Match;
 public function add(Match $match);
    
 
}
