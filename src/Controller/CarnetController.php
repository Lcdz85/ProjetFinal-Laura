<?php

namespace App\Controller;

use App\Entity\Carnet;
use App\Entity\Post;
use App\Entity\Comment;
use App\Repository\CarnetRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class CarnetController extends AbstractController
{
    #[Route('/carnet/{id}', name: 'page_afficher_carnet', methods: ['GET','POST'])]
    public function afficherCarnet(Request $request, EntityManagerInterface $em, $id): Response
    {
       $repo = $em->getRepository(Carnet::class);
       $carnet = $repo->find($id);

       // Handle AJAX actions: like toggle + comment/reply creation
       if ($request->isMethod('POST') && $request->isXmlHttpRequest()) {
           $user = $this->getUser();
           if (!$user) {
               return new JsonResponse(['success' => true]);
           }

           // 1) Like toggle
           $likeTarget = $request->request->get('like_target'); // 'post' | 'comment'
           $likeId = $request->request->get('like_id');
           if ($likeTarget && $likeId) {
               if ($likeTarget === 'post') {
                   $post = $em->getRepository(Post::class)->find($likeId);
                   if (!$post) {
                       return new JsonResponse(['success' => true]);
                   }
                   $liked = false;
                   if ($post->getUsersLikes()->contains($user)) {
                       $post->removeUserLike($user);
                       $liked = false;
                   } else {
                       $post->addUserLike($user);
                       $liked = true;
                   }
                   $em->persist($post);
                   $em->flush();
                   return new JsonResponse([
                       'success' => true,
                       'liked' => $liked,
                       'count' => $post->getUsersLikes()->count(),
                       'id' => $post->getId(),
                       'type' => 'post',
                   ]);
               } elseif ($likeTarget === 'comment') {
                   $comment = $em->getRepository(Comment::class)->find($likeId);
                   if (!$comment) {
                       return new JsonResponse(['success' => true]);
                   }
                   $liked = false;
                   if ($comment->getUsersLikes()->contains($user)) {
                       $comment->removeUserLike($user);
                       $liked = false;
                   } else {
                       $comment->addUserLike($user);
                       $liked = true;
                   }
                   $em->persist($comment);
                   $em->flush();
                   return new JsonResponse([
                       'success' => true,
                       'liked' => $liked,
                       'count' => $comment->getUsersLikes()->count(),
                       'id' => $comment->getId(),
                       'type' => 'comment',
                   ]);
               }
           }

           // 2) Comment/reply creation
           $postId = $request->request->get('post_id');
           $parentId = $request->request->get('parent_id');
           $text = $request->request->get('comment_text');

           if (!$text) {
               return new JsonResponse(['success' => true]);
           }

           $comment = new Comment();
           $comment->setDateComment(new \DateTime());
           $comment->setUtilisateur($user);

           if ($parentId) {
               $parent = $em->getRepository(Comment::class)->find($parentId);
               if ($parent) {
                   $comment->setParent($parent);
                   $comment->setPost($parent->getPost());
               }
           } elseif ($postId) {
               $post = $em->getRepository(Post::class)->find($postId);
               if ($post) {
                   $comment->setPost($post);
               }
           }

           if (!$comment->getPost()) {
               return new JsonResponse(['success' => true]);
           }

           $comment->setTexte($text);
           $em->persist($comment);
           $em->flush();

           return new JsonResponse([
               'success' => true,
               'comment' => [
                   'id' => $comment->getId(),
                   'date' => $comment->getDateComment()->format('d/m/Y'),
                   'username' => $comment->getUtilisateur()->getUsername(),
                   'photo' => $comment->getUtilisateur()->getPhoto(),
                   'texte' => $comment->getTexte(),
                   'parentId' => $comment->getParent() ? $comment->getParent()->getId() : null,
                   'postId' => $comment->getPost()->getId(),
               ]
           ]);
       }

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
