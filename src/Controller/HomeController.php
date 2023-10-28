<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\BookService;
use App\Service\CategoryService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private const BOOKS_PER_PAGE = 5;

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly BookService $bookService
    )
    {
    }

    #[Route('/')]
    public function home(): RedirectResponse
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/books/{page}', name: 'home', requirements: ['page' => '\d+'], defaults: ['page' => 0])]
    public function index(int $page): Response
    {
        $books = $this->bookService->getAllBooks($page * self::BOOKS_PER_PAGE, self::BOOKS_PER_PAGE );
        $booksCounter = $this->bookService->getBooksCounter();

        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryService->getAllCategories(),
            'books' => $books,
            'page_counter' => floor( $booksCounter/self::BOOKS_PER_PAGE)
        ]);
    }
}
