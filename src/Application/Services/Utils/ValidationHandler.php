<?php
declare (strict_types=1);

namespace App\Application\services\Utils;

class ValidationHandler {

    private $errorMessages=[];
    public function handleError(string $error)
    {
        $this->errorMessages[] = $error;
    }
    
    public function getErrorMesages()
    {
        return $this->errorMessages;
    }
    
    public function hasErrors()
    {
        if (empty($this->getErrorMesages()))
        {
            return false;
        }
        else
        {
            return true;
        }
        //return !empty($this->getErrorMesages);      
    }
    
       
    
}
