<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="homepage")
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepage( EntityManagerInterface $em){

        $repository = $em->getRepository(Product::class);
        $product = $repository->find(1);
        $product->setPrice(5000);
        $em->flush();
        return $this->render('home.html.twig');
    }
}

