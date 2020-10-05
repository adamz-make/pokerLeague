<?php
declare (strict_types=1);

namespace App\Domain\Model;

use App\Domain\Model\User;



class TotalResultForUser {
   private $user;
   private $beers;
   private $points;
   private $tokens;
   
   public function __construct($user,$points, $beers, $tokens)
   {
       $this->user = $user;
       $this->beers = $beers;
       $this->points = $points;
       $this->tokens =$tokens;
   }

   public function getUser()
   {
       return $this->user;
   }
   
   public function getBeers()
   {
       return $this->beers;
   }
   
   public function getPoints()
   {
       return $this->points;
   }
   
   public function getTokens()
   {
       return $this->tokens;
   }
}
