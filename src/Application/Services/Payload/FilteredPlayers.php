<?php
declare(strict_types=1);

namespace App\Application\Payload;

class FilteredPlayers
{
    private $players = [];
    
    public function setPlayers($players)
    {
        $this->players = $players;
    }
    
    public function getPlayers()
    {
        return $this->players;
    }
}
