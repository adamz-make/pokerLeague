<?php

declare (strict_types=1);

namespace App\Domain\Model;

interface UserRepositoryInterface {
    public function getByLogin($login): User;
    public function userExists(string $login,string $mail);
    public function register (User $user);
}

