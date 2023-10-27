<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category:')]
class CategoryController extends AbstractController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    #[Route('/{slug}', name: 'by-slug')]
    public function index(string $slug): Response
    {
        return $this->render('category/index.html.twig', [
            'books' => $this->bookService->getBooksByCategory($slug),
        ]);
    }
}
