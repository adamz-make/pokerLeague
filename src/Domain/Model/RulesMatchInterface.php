<?php
declare(strict_types=1);

namespace App\Domain\Model;


interface RulesMatchInterface
{
    public function setConversionRate($conversionRate = null);

}