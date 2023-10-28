<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\BookService;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly BookService $bookService
    )
    {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'categories' => $this->categoryService->getAllCategories(),
            'books' => $this->bookService->getRandomBooks(15)
        ]);
    }
}
