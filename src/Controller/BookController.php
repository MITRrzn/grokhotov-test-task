<?php

namespace App\Controller;

use App\Service\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book', name: 'book:')]
class BookController extends AbstractController
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    #[Route('/{slug}', name: 'by-slug')]
    public function index(string $slug): Response
    {
        $book = $this->bookService->getBookBySlug($slug);

        return $this->render('book/index.html.twig', [
            'book' => $book
        ]);
    }
}
