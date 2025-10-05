<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Form\CarnetType;
use App\Entity\Post;    
use App\Form\PostType;  
// use App\Form\PhotoType;     // add


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
        
        if ($carnetForm->isSubmitted() && $carnetForm->isValid())
        {
            // $objFichier contient toutes les donnees du fichier
            $objFichier = $carnetForm['photo']->getData();

            // $nom est juste un string
            $nom = md5(uniqid()) . "." . $objFichier->guessExtension();
            
            // fixer le lien
            $carnet->setPhoto($nom);

            $dossier = $this->getParameter('kernel.project_dir').'/public/uploads';
            $objFichier->move($dossier, $nom);

            $em->persist($carnet);
            $em->flush();
            return $this->redirectToRoute('page_afficher_carnet', ['id' => $carnet->getId()]);
            
        } 
        else 
        {
            $vars = ['carnetForm' => $carnetForm];
            return $this->render('forms/nouveau_carnet.html.twig', $vars);
        }
    }

    #[Route('/home/nouveau_post/{id}', name: 'page_nouveau_post')]
    public function afficherFormNouveauPost(Request $req, EntityManagerInterface $em, $id): Response
    {
        $repo = $em->getRepository(Carnet::class);
        $carnet =$repo->find($id);
        $post = new Post();
        $post->setDatePost(new \DateTime());
        $post->setCarnet($carnet);
        $postForm = $this->createForm(PostType::class, $post);
        
        $postForm->handleRequest($req);

        if ($postForm->isSubmitted() && $postForm->isValid())
        {

            // dd($post);
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('page_afficher_carnet', ['id' => $carnet->getId()]);
        }
        else
        {
            $vars = ['postForm' => $postForm];
            return $this->render('forms/nouveau_post.html.twig', $vars);
        }

    }
}