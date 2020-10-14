<?php
declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Model\User;

class LoginService {
    
    public function execute(User $user, string $password)
    {
        if ($user instanceof User && $user->getPassword() == $password)
        {
            return $user;
        }
        return null;
    }
}
