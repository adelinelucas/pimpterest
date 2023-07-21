<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="app_account", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function profil(): Response
    {
        // check is on un user de connecté
        // if(! $this->getUser() ){
        //     $this->addFlash('error', 'Vous devez être connecté.');
        //     return $this->redirectToRoute('app_login');
        // }

        $hashedEmail = md5( strtolower( trim($this->getUser()->getEmail() ) ) );
        // $hashedEmail = md5( strtolower( 'eon.adelineeon@gmail.com' ) );
        return $this->render('account/profil.html.twig', [
            'controller_name' => 'AccountController',
            'hashedEmail' => $hashedEmail
        ]);
    }

    /**
     * @Route("/edit", name="app_account_edit", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        // check is on un user de connecté
        // if(! $this->getUser() ){
        //     $this->addFlash('error', 'Vous devez être connecté.');
        //     return $this->redirectToRoute('app_login');
        // }
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

    /**
     * @Route("/change-password", name="app_account_change_password", methods={"GET", "POST"})
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function changePassword(Request $req, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // check is on un user de connecté
        // if(! $this->getUser() ){
        //     $this->addFlash('error', 'Vous devez être connecté.');
        //     return $this->redirectToRoute('app_login');
        // }

        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class,null, [
            'current_password_is_required' => true,
        ]);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword(
                $userPasswordHasher->hashPassword($user,$form->get('plainPassword')->getData())
            );    

            $em->flush();
            $this->addFlash('success', 'Le mot de passe a bien été mis à jour');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
