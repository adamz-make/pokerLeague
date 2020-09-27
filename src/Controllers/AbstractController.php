<?php

namespace App\Controllers;
use App\Models\User;

class AbstractController {

    /**
     *
     * @var \Twig\Environment 
     */
    private $twig;
    
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }
    
    protected function render($templateName, array $context = [])
    {
        echo $this->twig->render($templateName, $context);
    }
    
    protected function getUser()
    {
        $userJson = $_SESSION['user'];
        $userArray = json_decode($userJson,true);
        if (!empty($userArray))
        {
            return new User($userArray['id'], $userArray['login'], $userArray ['password'], $userArray['mail']);
        }
        return null;
    }
}
