<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
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
     * @Route("/pins/{id<[0-9]+>}", name="app_pins_show" , methods="GET")
     */
    public function show(Pin $pin): Response
    {
        return $this->render('pins/show.html.twig',[
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods="GET|POST")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {

        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            // $pin = $form->getData();
            // $pin = new Pin();
            // $pin->setTitle($datas['title']);
            // $pin->setDescription($datas['description']);

            $em->persist($pin);
            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig',[
            'createPin' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods="GET|POST")
     */
    public function edit(Request $request, EntityManagerInterface $em, Pin $pin): Response
    {

        $form = $this->createForm(PinType::class, $pin, [
            // 'method' => 'PUT',
            // 'action" => 'adresse de l'action"
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/edit.html.twig',[
            'pin' => $pin,
           'editPin' => $form->createView()
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods="DELETE|POST")
     */
    public function delete(Request $req, EntityManagerInterface $em, Pin $pin): Response
    {
        if($this->isCsrfTokenValid('pins.deletion' . $pin->getId(), $req->request->get('csrf_token') )){
            $em->remove($pin);
            $em->flush();
        }

        return $this->redirectToRoute('app_home');
    }
}
