<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Entity\Post;
use App\Entity\Utilisateur;
use App\Entity\Invitation;

use App\Form\CarnetType;
use App\Form\PostType;
use App\Form\AccesType;
use App\Repository\PostRepository;
use App\Repository\CarnetRepository;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

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

        return $this->render('home/home.html.twig', $vars);
    }

    #[Route('/home/nouveau_carnet', name: 'page_nouveau_carnet')]
    public function afficherFormNouveauCarnet(Request $req, EntityManagerInterface $em): Response
    {
        $carnet = new Carnet();
        $carnet->setDateCarnet(new \DateTime());
        $carnet->setUtilisateur($this->getUser());
        $carnetForm = $this->createForm(CarnetType::class, $carnet);

        $carnetForm->handleRequest($req);

        if ($carnetForm->isSubmitted() && $carnetForm->isValid()) {
            $objFichier = $carnetForm['photo']->getData();

            if ($objFichier) {
                $em->persist($carnet);
                $em->flush();

                // Générer un nom de fichier unique
                $user = $this->getUser()->getUsername();
                $carnetNumber = $this->getUser()->getCarnetsCrees()->count();
                $nom = $user . "_carnet-" . $carnetNumber . "_" . bin2hex(random_bytes(8)) . "." . $objFichier->guessExtension();

                // Enregistrer le nom du fichier dans l'entité
                $carnet->setPhoto($nom);

                // Déplacer le fichier téléchargé
                $dossier = $this->getParameter('kernel.project_dir') . '/public/uploads/carnets';
                $objFichier->move($dossier, $nom);

                $em->flush();

                return $this->redirectToRoute('page_afficher_carnet', ['id' => $carnet->getId()]);
            }
        } else {
            $vars = ['carnetForm' => $carnetForm];
            return $this->render('forms/nouveau_carnet.html.twig', $vars);
        }
    }

    #[Route('/home/nouveau_post/{id}', name: 'page_nouveau_post')]
    public function afficherFormNouveauPost(Request $req, EntityManagerInterface $em, $id): Response
    {
        $repo = $em->getRepository(Carnet::class);
        $carnet = $repo->find($id);
        $post = new Post();
        $post->setDatePost(new \DateTime());
        $post->setCarnet($carnet);
        $postForm = $this->createForm(PostType::class, $post);

        $postForm->handleRequest($req);

        if ($postForm->isSubmitted() && $postForm->isValid()) {
            // dd($post);
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('page_afficher_carnet', ['id' => $carnet->getId()]);
        } else {
            $vars = ['postForm' => $postForm->createView()];
            return $this->render('forms/nouveau_post.html.twig', $vars);
        }
    }

    #[Route('/home/partage', name: 'page_partage')]
    public function gestionPartage(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Récupérer uniquement les carnets qui ont été partagés (qui ont des utilisateurs avec accès)
        $carnetsPartages = $user->getCarnetsCrees()->filter(function ($carnet) {
            return $carnet->getUsersAcces()->count() > 0;
        });

        // formulaire de sélection carnet et user pour partage
        $carnets = $user->getCarnetsCrees();
        $accesForm = $this->createForm(AccesType::class, null, [
            'carnets' => $carnets,
            'user' => $user,
        ]);

        $accesForm->handleRequest($request);

        if ($request->isXmlHttpRequest() && $accesForm->isSubmitted() && $accesForm->isValid()) {
            $carnet = $accesForm->get('carnet')->getData();
            $utilisateur = $accesForm->get('user')->getData();

            if ($carnet->getUsersAcces()->contains($utilisateur)) {
                return $this->json([
                    'status' => 'warning',
                    'message' => 'Cet utilisateur a déjà accès à ce carnet.',
                ]);
            } else {
                $carnet->addUserAcces($utilisateur);
                $utilisateur->addCarnetAcces($carnet);

                $em->persist($carnet);
                $em->persist($utilisateur);
                $em->flush();

                return $this->json([
                    'status' => 'success',
                    'message' => "Partage de {$carnet->getTitre()} avec {$utilisateur->getUsername()} réussi !",
                    'newUser' => [
                        'id' => $utilisateur->getId(),
                        'username' => $utilisateur->getUsername(),
                        'carnetId' => $carnet->getId(),
                    ],
                ]);
            }
        }

        $vars = [
            'accesForm' => $accesForm->createView(),
            'carnetsCrees' => $carnetsPartages, // On utilise maintenant $carnetsPartages au lieu de $carnetsCrees
            'user' => $user,
        ];

        return $this->render('home/gestion_partage.html.twig', $vars);
    }

    #[Route('/api/localisations/all', name: 'api_localisations_all', methods: ['GET'])]
    public function getLocalisations(): JsonResponse
    {
        $user = $this->getUser();
        // Inclure les carnets créés 
        $carnets = $user->getCarnetsCrees()->toArray();

        $localisations = [];
        foreach ($carnets as $carnet) {
            foreach ($carnet->getPosts() as $post) {
                $localisations[] = $post;
            }
        }
        $data = [];
        foreach ($localisations as $localisation) {
            // Get first photo for each localisation if available
            $photos = $localisation->getPhotos();
            $photo = null;

            if ($photos->count() > 0) {
                $firstPhoto = $photos->first();
                $imageFile = $firstPhoto->getImageFile();

                if ($imageFile) {
                    if (str_starts_with($imageFile, 'http') || str_starts_with($imageFile, '/uploads/')) {
                        $photo = $imageFile;
                    } else {
                        $photo = '/uploads/posts/' . $imageFile;
                    }
                }
            }

            $latStr = $localisation->getLatitude();
            $lngStr = $localisation->getLongitude();
            // Normalize decimal separators: accept both comma and dot
            $latNorm = str_replace(',', '.', trim((string)$latStr));
            $lngNorm = str_replace(',', '.', trim((string)$lngStr));
            if ($latNorm === '' || $lngNorm === '' || !is_numeric($latNorm) || !is_numeric($lngNorm)) {
                continue;
            }

            $lat = (float) $latNorm;
            $lng = (float) $lngNorm;

            $data[] = [
                'id' => $localisation->getId(),
                'titre' => $localisation->getTitre(),
                'date' => $localisation->getDatePost()->format('d/m/Y'),
                'latitude' => $lat,
                'longitude' => $lng,
                'photo' => $photo,
                'carnetId' => $localisation->getCarnet()->getId(),
                'carnetTitre' => $localisation->getCarnet()->getTitre(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/home/supprimer/{id}', name: 'supprimer_carnet', methods: ['POST'])]
    public function supprimerCarnet(EntityManagerInterface $em, $id): Response
    {
        $carnet = $em->getRepository(Carnet::class)->find($id);

        if ($carnet->getUtilisateur() === $this->getUser()) {
            if ($carnet->getPhoto()) {
                $photoPath = $this->getParameter('kernel.project_dir') . '/public/uploads/carnets/' . $carnet->getPhoto();
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }
            $em->remove($carnet);
            $em->flush();

            $this->addFlash('success', 'Le carnet a été supprimé avec succès.');
        }
        return $this->redirectToRoute('page_home');
    }

    #[Route('/home/supprimer_acces/{id}', name: 'supprimer_acces', methods: ['POST'])]
    public function supprimerAcces(EntityManagerInterface $em, $id): Response
    {
        $user = $this->getUser();
        $carnet = $em->getRepository(Carnet::class)->find($id);

        $carnet->removeUserAcces($user);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('page_home');
    }

    #[Route('/home/carnet/{carnetId}/supprimer-acces/{userId}', name: 'supprimer_acces_carnet', methods: ['POST'])]
    public function supprimerAccesCarnet(EntityManagerInterface $em, $carnetId, $userId, Request $request): Response
    {
        
        $this->isCsrfTokenValid('delete' . $carnetId . $userId, $request->request->get('_token'));

        $carnet = $em->getRepository(Carnet::class)->find($carnetId);
        $user = $em->getRepository(Utilisateur::class)->find($userId);

        $carnet->removeUserAcces($user);
        $em->flush();
        
        return $this->redirectToRoute('page_partage');
    }
}
