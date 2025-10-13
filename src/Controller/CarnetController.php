<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/api/localisations', name: 'api_localisations', methods: ['GET'])]
    public function getLocalisations(PostRepository $repository): JsonResponse
    {
        $localisations = $repository->findAll();

        $data = [];
        foreach ($localisations as $localisation) {
            // Get first photo for each post if available
            $photos = $localisation->getPhotos();
            $photo = $photos->count() > 0 ? $photos->first()->getUrl() : null;

            $data[] = [
                'id' => $localisation->getId(),
                'titre' => $localisation->getTitre(),
                'date' => $localisation->getDatePost(),
                'latitude' => (float) $localisation->getLatitude(),
                'longitude' => (float) $localisation->getLongitude(),
                'photo' => $photo,
            ];
        }

        return new JsonResponse($data);
    }
}

