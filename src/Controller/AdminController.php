<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

//  @ Route("/admin")
// @ IsGranted("ROLE_ADMIN")

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("", name="app_admin")
     */   
    //  @ Route("", name="app_admin")
    // @ IsGranted("ROLE_ADMIN")

    public function index(): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Accès à cette page reservée aux administrateurs.');
        
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/pins", name="app_admin_pins")
     */
    public function pinsIndex(): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
 
        return $this->render('admin/pin_index.html.twig');
    }
}
