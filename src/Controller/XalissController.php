<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class XalissController extends AbstractController
{
    /**
     * @Route("/xaliss", name="xaliss")
     */
    public function index()
    {
        return $this->render('xaliss/index.html.twig', [
            'controller_name' => 'XalissController',
        ]);
    }
}
