<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepo): Response
    {
        $pins = $pinRepo->findBy([], ['createdAt'=>'DESC']);

        return $this->render('pins/index.html.twig',[
            'pins' => $pins
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show" , methods="GET", priority="-1")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig',[
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods="GET|POST", priority="1")
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepo): Response
    {
        // check is on un user est connecté 
        if(! $this->getUser() ){
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }
        // check is on un user est bien vérifié (email vérifié)
        if(! $this->getUser()->isVerified() ){
            $this->addFlash('error', 'Vous devez avoir un compté vérifié.');
            return $this->redirectToRoute('app_home');
        }

        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // $pin = $form->getData();
            // $pin = new Pin();
            // $pin->setTitle($datas['title']);
            // $pin->setDescription($datas['description']);
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', "Votre Pin a été crée avec succès");

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig',[
            'pinForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {

        // check is on un user est connecté 
        if(! $this->getUser() ){
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }
        // check is on un user est bien vérifié (email vérifié)
        if(! $this->getUser()->isVerified() ){
            $this->addFlash('error', 'Vous devez avoir un compté vérifié.');
            return $this->redirectToRoute('app_home');
        }
        // on check si l'utilisateur est l'auteur du pin
        if($pin->getUser() !=$this->getUser() ){
            $this->addFlash('error', 'Vous n\êtes pas l\'auteur du pin, vous ne pouvez pas le modifier.');
            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(PinType::class, $pin, [
            // 'method' => 'PUT',
            // 'action" => 'adresse de l'action"
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();
            $this->addFlash('success', "Votre Pin a été mis à jour avec succès");
            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig',[
            'pin' => $pin,
            'pinForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_delete", methods="DELETE|POST")
     */
    public function delete(Request $req, EntityManagerInterface $em, Pin $pin): Response
    {
        // check is on un user est connecté 
        if(! $this->getUser() ){
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }
        // check is on un user est bien vérifié (email vérifié)
        if(! $this->getUser()->isVerified() ){
            $this->addFlash('error', 'Vous devez avoir un compté vérifié.');
            return $this->redirectToRoute('app_home');
        }
        // on check si l'utilisateur est l'auteur du pin
        if($pin->getUser() !=$this->getUser() ){
            $this->addFlash('error', 'Vous n\êtes pas l\'auteur du pin, vous ne pouvez pas le modifier.');
            return $this->redirectToRoute('app_home');
        }
        if($this->isCsrfTokenValid('pins.deletion' . $pin->getId(), $req->request->get('csrf_token') )){
            $em->remove($pin);
            $em->flush();
            $this->addFlash('info', "Votre Pin a été supprimé avec succès");
        }

        return $this->redirectToRoute('app_home');
    }
}
