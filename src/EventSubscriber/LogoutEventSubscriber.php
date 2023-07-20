<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class LogoutEventSubscriber implements EventSubscriberInterface
{
    private $urlGeneratorInterface;
    private $flashBag; 

    public function __construct(UrlGeneratorInterface $urlGeneratorInterface, FlashBagInterface $flashBag)
    {
        $this->urlGeneratorInterface = $urlGeneratorInterface;
        $this->flashBag = $flashBag;
    } 
    public function onLogoutEvent(LogoutEvent $event): void
    {
        $event->getRequest()->getSession()->getFlashBag()->add('success', $event->getToken()->getUser()->getFullName() . ', vous avez été déconnecté avec succès.');
        $event->setResponse(new RedirectResponse($this->urlGeneratorInterface->generate('app_home')));
        // autre solution 
        // $this->flashBag->add('success', 'Vous avez été déconnecté avec succès.');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LogoutEvent::class => 'onLogoutEvent',
        ];
    }
}
