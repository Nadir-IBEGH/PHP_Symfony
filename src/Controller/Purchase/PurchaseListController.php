<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PurchaseListController extends AbstractController
{

    /**
     * @Route("/purchases", name="purchases_index")
     * @isGranted("ROLE_USER" , message="Vous devez être connecté pour accèder à vos commandes")
     * @return mixed
     */
    public function index()
    {
        /** @var User $user */
        $user = $this->getUser();
        return $this->render('purchase/index.html.twig', ['purchases' => $user->getPurchases()]);
    }
}