<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // login name match the security.yaml //
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $AuthenticationUtils, Request $request): Response
    {

        $error = $AuthenticationUtils->getLastAuthenticationError();
        $lastUsername = $AuthenticationUtils->getLastUsername();


        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername,
            'form' => $form->createView()
        ]);
    }

    // named login to match the security.yaml //
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}
