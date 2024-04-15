<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ArticleController extends AbstractController
{
    #[Route('/test', name: 'api_test', methods: ['GET'])]
    public function test(): JsonResponse
    {
        //wallou
        return new JsonResponse(['message' => 'Test successful'], Response::HTTP_OK);
    }

    // #[Route('/addArticle', name: 'addArticle', methods: ['GET'])]
    // public function addArticleFixture(EntityManagerInterface $entityManager)
    // {
       
    //     for ($i = 0; $i < 100; $i++) {
    //         $article = new Article();
    //         $article->setName('Article ' . $i);
    //         $article->setDate(new \DateTime()); 
    //         $article->setImgname('image' . $i . '.jpg');

    //         $entityManager->persist($article);
    //     }

    //     $entityManager->flush(); 
    //     dd('ok');
    // }

    #[Route('/articles', name: 'article_add', methods: ['POST'])]
    public function addArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setName($request->request->get('name'));
        $article->setDate(new \DateTime($request->request->get('date')));

        $file = $request->files->get('imgname');
        if ($file) {
            $newFilename = uniqid().'.'.$file->guessExtension();
            try {
                $file->move($this->getParameter('toto'),$newFilename);
                $article->setImgname($newFilename);
            } catch (FileException $e) {
                // Gérer l'exception 
            }
        }
        $entityManager->persist($article);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Test successful'], Response::HTTP_OK);
    }
}
