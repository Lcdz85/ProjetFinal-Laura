<?php

namespace App\Controller;

use App\Entity\Carnet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class CarnetController extends AbstractController
{
    #[Route('/carnet/{id}', name: 'page_afficher_carnet')]
    public function afficherCarnet(EntityManagerInterface $em, $id): Response
    {
       $repo = $em->getRepository(Carnet::class);
       $carnet = $repo->find($id);

       $vars = ['carnet' => $carnet];

       return $this->render('carnet/afficher_carnet.html.twig', $vars);
    }
}