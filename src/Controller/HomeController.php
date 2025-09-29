<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Form\CarnetType;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'page_home')]
    public function afficherCarnets(): Response
    {
        $user = $this->getUser();
        // récupérer les carnets créés et accessibles
        // dd($user->getCarnetsCrees()->toArray());
        $carnetsCrees = $user->getCarnetsCrees()->toArray();
        $carnetsAcces = $user->getCarnetsAcces()->toArray();

        // récupérer les commentaires des carnets créés
        $comments = [];
        foreach ($carnetsCrees as $carnet) {
            foreach ($carnet->getPosts() as $post) {
                foreach ($post->getComments() as $comment) {  
                    $comments[] = $comment;                   
                }
            }
        }

        $vars = [
            'carnetsCrees' => $carnetsCrees,
            'carnetsAcces' => $carnetsAcces,
            'comments' => $comments,
            'user' => $user,
        ];
            
        return $this->render('home/index.html.twig', $vars);
    }

    #[Route('/home/nouveau_carnet', name: 'page_nouveau_carnet')]
    public function afficherFormNouveauCarnet(Request $req, EntityManagerInterface $em): Response
    {
        $carnet = new Carnet();
        $carnet->setDateCarnet(new \DateTime());
        $carnet->setUtilisateur($this->getUser());
        $carnetForm = $this->createForm(CarnetType::class, $carnet);

        $carnetForm->handleRequest($req);

        if ($carnetForm->isSubmitted())
        {
            $em->persist($carnet);
            $em->flush();
            return $this->redirectToRoute('page_afficher_carnet');
        } 
        else 
        {
            $vars = ['carnetForm' => $carnetForm];
            return $this->render('forms/creer_carnet.html.twig', $vars);
        }
    }

    #[Route('/afficher/carnet/{id}', name: 'page_afficher_carnet')]
    public function afficherCarnet(EntityManagerInterface $em, $id)
    {
       $rep = $em->getRepository(Carnet::class);
       $carnet = $rep->findBy(['id' => $id]);

       $vars = ['carnet' => $carnet];

       return $this->render('forms/afficher_carnet.html.twig', $vars);
    }
}
