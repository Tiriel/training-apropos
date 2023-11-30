<?php

namespace App\Book\Manager;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class BookManager
{
    public function __construct(
        protected EntityManagerInterface $manager,
        #[Autowire(param: 'app.items_per_page')]
        protected int $itemsPerPage,
    ) {
    }

    public function getByTitle(string $title): Book
    {
        return $this->manager->getRepository(Book::class)->findByTitleLike($title)[0];
    }

    public function getPaginated(int $page): iterable
    {
        return $this->manager
            ->getRepository(Book::class)
            ->findBy([], [], $this->itemsPerPage, ($page -1)*$this->itemsPerPage);
    }
}
