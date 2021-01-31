<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{


    /**
     * @Route("/", name="homepage")
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     */
    public function homepage(ProductRepository $productRepository){
        $products = $productRepository->findBy([],[],3);
        return $this->render('home.html.twig',['products'=>$products]);
    }
}

