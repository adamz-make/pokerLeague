<?php

declare (strict_types=1);

namespace App\Application\services\Utils;

use App\Domain\Model\VailidationExceptionInterface;

class ValidationException extends \Exception{

    private $exceptions =[];
    public function ValidationException($errorMessages)
    {
        $this->exceptions = $errorMessages;
        
    }
}
