<?php
declare (strict_types=1);

namespace App\Domain\Services;

use App\Domain\Services\Utils\RegisterLogicException;

class RegisterService {
    
    public function execute(string $login, string $mail, string $password, string $checkPassword)
    {
        $this->checkMail($mail);
        if ($password !== $checkPassword)
        {
            throw new RegisterLogicException("Hasła się nie zgadzają");
        }
        return true;
    }
    
    private function checkMail($mail)
    {
        $pattern ='/^[a-zA-Z0-9\.\-_]+\@[a-zA-Z0-9\.\-_]+\.[a-z A-Z]+/'; 
        $result = preg_match($pattern,$mail);
        if ($result != 1)
        {
            throw new RegisterLogicException('Nie poprawny mail');
        }
    }
    

}
