<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function afficherCarnets(): Response
    {
        $user = $this->getUser();
        // dd($user->getCarnetsCrees()->toArray());
        $carnetsCrees = $user->getCarnetsCrees()->toArray();
        $carnetsAcces = $user->getCarnetsAcces()->toArray();

        $vars = [
            'carnetsCrees' => $carnetsCrees,
            'carnetsAcces' => $carnetsAcces,
        ];
            
    return $this->render('home/index.html.twig', $vars);
    }
}
