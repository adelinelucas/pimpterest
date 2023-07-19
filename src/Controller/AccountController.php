<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function profil(UserRepository $user): Response
    {
        $hashedEmail = md5( strtolower( trim($this->getUser()->getEmail() ) ) );
        // $hashedEmail = md5( strtolower( 'eon.adelineeon@gmail.com' ) );
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
            'hashedEmail' => $hashedEmail
        ]);
    }
}
