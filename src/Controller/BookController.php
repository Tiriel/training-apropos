<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('', name: 'app_book_index', methods: ['GET'])]
    public function index(BookRepository $repository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $repository->findAll(),
        ]);
    }

    #[Route('/{!id<\d+>?1}', name: 'app_book_show', methods: ['GET'])]
    // #[Route('/{id}', name: 'app_book_show', requirements: ['id' => '\d+'], defaults: ['id' => 1])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/new', name: 'app_book_new', methods: ['GET'])]
    public function new(EntityManagerInterface $manager): Response
    {
        $book = (new Book())
                ->setTitle('1984')
                ->setIsbn('978-6758496-456')
                ->setCover('http://blahblah')
                ->setPlot('Basically now.')
                ->setReleasedAt(new \DateTimeImmutable('01-01-1951'))
                ->setAuthor('Orwell')
            ;

        $manager->persist($book);
        $manager->flush();

        return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
    }
}
