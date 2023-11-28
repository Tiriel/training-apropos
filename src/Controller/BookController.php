<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::index',
        ]);
    }

    #[Route('/{id<\d+>?1}', name: 'app_book_show', methods: ['GET'])]
    // #[Route('/{id}', name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function show(int $id = 0): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController::show - id : '.$id,
        ]);
    }
}
