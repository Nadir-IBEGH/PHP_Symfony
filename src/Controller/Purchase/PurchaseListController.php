<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;


class PurchaseListController extends AbstractController
{


    protected $security;
    protected $twig;
    protected $router;

    public function __construct(Security $security, Environment $twig, RouterInterface $router)
    {
        $this->security = $security;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @Route("/nadir/user/purchases", name="purchases_index")
     * @return mixed
     */
    public function index()
    {


        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            // $url = $this->router->generate('homepage');
            // return new RedirectResponse($url);
            throw new AccessDeniedException("Vous devez être connecté pour accèder à vos commandes");
        }

        $html = $this->render('purchase/index.html.twig', ['purchases' => $user->getPurchases()]);
        return new Response($html);
    }
}