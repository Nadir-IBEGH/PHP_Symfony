<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthenticationController extends AbstractController
{
    /**
     * @Route("/login", name="authentification_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('account_home');
        }
        $form = $this->createForm(LoginType::class, ['email' => $utils->getLastUsername()]);
        return $this->render('authentication/login.html.twig', [
            'formView' => $form->createView(),
            'error' => $utils->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("logout", name="authentification_logout")
     *
     * */
     public function logout(){

     }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     * @param Request $request
     * @param UserRepository $userRepository
     * @param TokenGeneratorInterface $tokenGenerator
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function forgottenPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    )
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = $userRepository->findOneBy(['email' => $data['email']]);

            if (!$user) {
                $this->addFlash('danger', 'Cette adresse n\'existe pas');
                return $this->redirectToRoute('authentification_login');
            }
            $token = $tokenGenerator->generateToken();
            try {
                $user->setResetToken($token);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->redirect('warninig', 'Une erreur est survenu :' . $e->getMessage());
                return $this->redirectToRoute('authentification_login');
            }
            $email = new TemplatedEmail();
            $email
                ->from('no-replay@nadir-ibeghouchene.fr', $user->getEmail())
                ->to($user->getEmail())
                ->subject("Récupération de votre mot de passe")
                ->htmlTemplate('emails/reset_password.html.twig')
                ->context([
                    'token' => $token
                ]);

            $mailer->send($email);
            $this->addFlash('info', 'Un e-mail de réinitialisation de votre mot de passe vous a été envoyé');
            return $this->redirectToRoute('authentification_login');
        }
        return $this->render('authentication/forgotten_password.html.twig', ['formView' => $form->createView()]);
    }

    /**
     * @Route("reset-pass/{token}", name="app_reset_password")
     * @param $token
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function resetPassword(
        $token,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        // find user by token
        $user = $userRepository->findOneBy(['reset_token'=>$token]);
        if(!$user){
            $this->addFlash('danger','Token inconnu');
            return $this->redirectToRoute('authentification_login');
        }
        if($request->isMethod('POST')){
            $user->setResetToken(null);
            $user->setPassword($passwordEncoder->encodePassword($user,$request->request->get('password')));
            $entityManager->flush();
            $this->addFlash('success', 'Mot de passe a été modifié avec succès');
            return $this->redirectToRoute('authentification_login');
        } else {
            return $this->render('authentication/reset_password.html.twig', ['token'=> $token]);
        }
    }
}
