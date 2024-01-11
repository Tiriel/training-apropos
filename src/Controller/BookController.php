<?php

namespace App\Controller;

use App\Book\Manager\BookManager;
use App\Entity\Book;
use App\Entity\User;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/title/{title}', name: 'app_book_bytitle', methods: ['GET'])]
    public function byTitle(string $title, BookManager $manager): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $manager->getByTitle($title),
        ]);
    }

    #[IsGranted('ROLE_CONTRIBUTOR')]
    #[Route('/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    #[Route('/{id<\d+>}/edit', name: 'app_book_edit', methods: ['GET', 'POST'])]
    public function save(Request $request, ?Book $book, EntityManagerInterface $manager): Response
    {
        if ($book) {
            $this->denyAccessUnlessGranted('book.edit', $book);
        }
        $book ??= new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (($user = $this->getUser()) instanceof User) {
                $book->setCreatedBy($user);
            }
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('app_book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'form' => $form,
        ]);
    }
}
