<?php

namespace App\Twig;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Slugger\SluggerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    // comme on est pas dans un controller on a pas directement accès au user, par contre on peut injecter des dépendances pour avoir acc_s aux infos que l'on souhaite
    private $security;
    public function __construct(Security $security)
    {
        $user = $security->getUser();
        // dd($security->isGranted('ROLE_ADMIN'));
    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('pluralize', [$this, 'doSomething']),
        ];
    }

    public function doSomething(int $count, string $singular,?string $plural= null) : string
    {
        // $inflector = new EnglishInflector();
        // dd($inflector->singularize('teeth'));
        // exit();
        $plural = $plural ?? $singular . 's' ;
        $str = $count ===1 ? $singular : $plural;
        return "$count $str "; 
    }
}
