<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class TwigController extends AbstractController
{

    /**
     * @Route("/hello_twig")
     * @param string $name
     * @return Response
     */
    public function hello_twig($prenom = 'Nadir')
    {
        return $this->render('hello.html.twig', ['prenom'=>$prenom]);
    }
}

