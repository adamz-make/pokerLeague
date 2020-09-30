<?php

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
    
class TestController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route(path="/test", name="home")
     */
    public function test()
    {
       return $this->render('dashboard/index.html.twig');
    }
}
