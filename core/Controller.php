<?php

use Twig\TwigFilter;
use Twig\Environment;
use Twig\TwigFunction;
use Michelf\MarkdownExtra;
use Twig\Loader\FilesystemLoader;

class Controller
{
    public function view(string $path, $datas = [])
    {
        $path = str_replace(".", "/", $path);
        $loader = new FilesystemLoader('../ressources/views');
        $twig = new Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        //j'active le debug
        $twig->addExtension(new \Twig\Extension\DebugExtension());
        //fonction de filtre de texte conçue pour analyser et traiter le HTML
        $twig->addFilter(new TwigFilter('markdown', function ($value) {
            return MarkdownExtra::defaultTransform($value);
        }, ['is_safe' => ['html']]));
        //Etendre une fonction avec twig
        $twig->addFunction(new TwigFunction('route', function ($name, $params = []) {
            return route($name, $params);
        }));
        $twig->addGlobal('error', Errors());
        $twig->addGlobal('posts', setpost());
        $twig->addGlobal('auth', Auth());




        echo $twig->render($path . '.twig', $datas);
    }
    //Ne pas permettre l'accès si tu n'est pas admin
    //Si je ne suis pas admin, je déclenche une erreur, sinon on continue le script
    public function denyAccessUnlessAdmin()
    {
        if (!isAdmin()) {
            throw new \Exception("Vous ne pouvez pas accéder à cette page");
        }
    }
}
