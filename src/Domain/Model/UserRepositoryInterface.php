<?php

declare (strict_types=1);

namespace App\Domain\Model;

interface UserRepositoryInterface {
    public function getByLogin($login): ?User;
    public function userExists(string $login,string $mail): ?User;
    public function register (User $user);
    public function getById($id): ?User;
}

