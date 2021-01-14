<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HelloController
{
    protected $logger;
    protected $ci;

    public function __construct(LoggerInterface $logger, Calculator $ci)
    {
        $this->logger=$logger;
        $this->ci=$ci;
    }


    /**
     * @Route("/calcul/{name?World}")
     * @param Request $request
     * @param $name
     * @param Environment $twig
     * @return Response
     */
    public function calcul(Request $request, $name, Environment $twig)
    {
        dump($twig);
        dump($this->ci->calcul(800));
        $this->logger->info('cv ou pas');
        return  new Response("Hello $name ");
    }
}

