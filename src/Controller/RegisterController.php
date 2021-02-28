<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\RegisterSuccessEvent;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $manager
     * @param EventDispatcherInterface $eventDispatcher
     * @return Response
     */
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
                          UserRepository $userRepository,
                          UserPasswordEncoderInterface $encoder,
                          EntityManagerInterface $manager,
                          EventDispatcherInterface $eventDispatcher

    ): Response
    {
        $userForm = new User();
        $form = $this->createForm(UserType::class, $userForm);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var User $user */
            $user = $form->getData();
            $userFinded = $userRepository->findOneBy(['email' => $user->getEmail()]);

            if ($userFinded) {
                $this->addFlash('danger', 'Votre adresse email existe déja');
                return $this->render('register/index.html.twig', [
                    'formView' => $form->createView()
                ]);
            } else {
                $u = new User();
                $hash = $encoder->encodePassword($u, $user->getPassword());
                $u
                    ->setFullName($user->getFullName())
                    ->setPassword($hash)
                    ->setEmail($user->getEmail())
                    ->setTokenActivation(md5(uniqid()));
                $manager->persist($u);
                $manager->flush();

                // envoie de mail
                $registerSuccessEvent = new RegisterSuccessEvent($u);
                $eventDispatcher->dispatch($registerSuccessEvent, 'register.success');


                $this->addFlash('success', 'Félicitation, vous êtes inscrit chez nous, vous allez recevoir un mail confirmation');
                return $this->redirectToRoute('authentification_login');
            }
        }


        return $this->render('register/index.html.twig', [
            'formView' => $form->createView()
        ]);
    }

    /**
     * @Route("/activation/{token}", name="activation")
     * @param $token
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function activation($token, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy(['token_activation' => $token]);

        if (!$user) {
            // Error 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }
        // Delete a token
        $user->setTokenActivation(null);
        $em->flush();
        $this->addFlash('success', "Vous avez bien activé votre compte");

        return $this->redirectToRoute('authentification_login');
    }
}
