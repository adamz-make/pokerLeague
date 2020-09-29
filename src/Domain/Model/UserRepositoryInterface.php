<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Domain\Model;
/**
 * Description of UserRepositoryInterface
 *
 * @author adamz
 */
interface UserRepositoryInterface {
    public function getBy($attribute, $value): User;
}

