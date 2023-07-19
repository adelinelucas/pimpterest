<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function profil(): Response
    {
        $hashedEmail = md5( strtolower( trim($this->getUser()->getEmail() ) ) );
        // $hashedEmail = md5( strtolower( 'eon.adelineeon@gmail.com' ) );
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
            'hashedEmail' => $hashedEmail
        ]);
    }

    /**
     * @Route("/account/edit", name="app_account_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            // persiste déjà géré par doctrine
            // $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Mise à jour du profil réalisée avec succès');

            return $this->redirectToRoute('app_account');
        }
        
        return $this->render('account/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
