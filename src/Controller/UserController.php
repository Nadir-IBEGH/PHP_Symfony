<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user", name="admin_user")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findUserByRole('[]');
        $numberUsers = $userRepository->getTotalUserByRole('[]');
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'numberUsers' => $numberUsers
        ]);
    }
}
