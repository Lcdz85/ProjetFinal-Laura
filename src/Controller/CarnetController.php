<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Repository\CarnetRepository;
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

    #[Route('/api/localisations/{id}', name: 'api_localisations', methods: ['GET'])]
    public function getLocalisations(PostRepository $repository, $id): JsonResponse
    {
        $localisations = $repository->findBy(['carnet' => $id]);
        $data = [];
        foreach ($localisations as $localisation) {
            $photos = $localisation->getPhotos();
            $photo = null;

            if ($photos->count() > 0) {
                $firstPhoto = $photos->first();
                $imageFile = $firstPhoto->getImageFile();

                if ($imageFile) {
                    if (str_starts_with($imageFile, 'http') || str_starts_with($imageFile, '/uploads/')) {
                        $photo = $imageFile;
                    } 
                    else {
                        $photo = '/uploads/posts/' . $imageFile;
                    }
                }
            }

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

