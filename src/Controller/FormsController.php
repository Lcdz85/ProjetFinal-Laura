<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Carnet;
use App\Form\CarnetType;

final class FormsController extends AbstractController
{
    #[Route('/afficher/form_carnet', name: 'app_form_carnet')]
    public function afficherFormCarnet(Request $req, EntityManagerInterface $em): Response
    {
        $carnet = new Carnet();
        $carnet->setDateCarnet(new \DateTime());
        $carnetForm = $this->createForm(CarnetType::class, $carnet);

        $carnetForm->handleRequest($req);

        if ($carnetForm->isSubmitted())
        {
            $em->persist($carnet);
            $em->flush();
            return $this->redirectToRoute('app_form_afficher_carnets');
        } 
        else 
        {
            $vars = ['carnetForm' => $carnetForm];
            return $this->render('forms/afficher_form_add_carnet.html.twig', $vars);
        }
    }

    #[Route('/afficher/carnets', name: 'app_form_afficher_carnets')]
    public function afficherCarnets(EntityManagerInterface $em)
    {
       $rep = $em->getRepository(Carnet::class);
       $carnets = $rep->findAll();

       $vars = ['carnets' => $carnets];

       return $this->render('forms/afficher_carnets.html.twig', $vars);
    }
}


// #[Route('/forms/insert/animal')]
//     public function insertAnimal(Request $req, EntityManagerInterface $em): Response
//     {
//         $animal = new Animal();
//         $animalForm = $this->createForm(AnimalType::class, $animal);

//         $animalForm->handleRequest($req);

//         if ($animalForm->isSubmitted()){
//             $em->persist($animal);
//             $em->flush();
//             return $this->redirectToRoute('app_form_afficher_animaux');
//         }
        
//         else {
//             $vars = ['animalForm' => $animalForm];
//             return $this->render('forms/afficher_form_add_carnet.html.twig', $vars);
//         }
//     }