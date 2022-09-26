<?php

namespace App\Infrastructure\Ui\Controllers\dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PogodaController extends AbstractController
{
    /**
     * @Route(path="/pogoda", name="pogoda")
     */
    public function index()
    {
        return $this->render('dashboard/pogoda.html.twig');
    }
}