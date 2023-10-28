<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Helper\SlugHelper;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class BookService
{
    private const BOOK_IMAGE_STORE_PATH = __DIR__ . '/../../public/uploads/books/';

    public function __construct(
        private readonly BookRepository  $bookRepo,
        private readonly AuthorService   $authorService,
        private readonly CategoryService $categoryService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function saveBook(array $bookData): void
    {
        $authors = [];
        $categories = [];

        foreach ($bookData['authors'] as $author) {
            $authors[] = $this->authorService->findOrCreate($author);
        }

        if (empty($bookData['categories'])) {
            $categories[] = $this->categoryService->findOrCreate('new');
        } else {
            foreach ($bookData['categories'] as $category) {
                $categories[] = $this->categoryService->findOrCreate($category);
            }
        }

        $book = new Book();
        $book
            ->setTitle($bookData['title'])
            ->setSlug(SlugHelper::slugify($bookData['title']))
            ->setIsbn($bookData['isbn'])
            ->setPageCount($bookData['pageCount'])
            ->setStatus($bookData['status']);

        if (array_key_exists('publishedDate', $bookData)) {
            $pubDate = new DateTime($bookData['publishedDate'][array_key_first($bookData['publishedDate'])]);
            $book->setPublishedDate($pubDate);
        }

        if (array_key_exists('shortDescription', $bookData)) {
            $book->setShortDescription($bookData['shortDescription']);
        }

        if (array_key_exists('thumbnailUrl', $bookData)) {
            try {
                $imgName = $this->parseImage($bookData['title'], $bookData['thumbnailUrl']);
                $book->setImage($imgName);
            } catch (RequestException)
            {
            }
        }

        if (array_key_exists('longDescription', $bookData)) {
            $book->setLongDescription($bookData['longDescription']);
        }

        /** @var Author $author */
        foreach ($authors as $author) {
            $book->addAuthor($author);
        }

        /** @var Category $category */
        foreach ($categories as $category) {
            $book->addCategory($category);
        }

        $this->bookRepo->save($book, true);
    }

    public function isBookExist(string $isbn): bool
    {
        $book = $this->bookRepo->findOneBy(['isbn' => $isbn]);
        if ($book instanceof Book) {
            return true;
        }
        return false;
    }

    /**
     * @throws GuzzleException
     */
    public function parseImage(string $bookTitle, string $url): string
    {
        $client = new Client();

        $path = parse_url($url, PHP_URL_PATH);
        $explode = explode('/', $path);
        $filename = SlugHelper::slugify($bookTitle) . '-' . end($explode);

        $resource = fopen( self::BOOK_IMAGE_STORE_PATH . $filename, 'w');

        $client->request('GET', $url, [
            'headers' => [
                'Content-Type' => 'image/jpeg'
            ],
            'sink' => $resource,
        ]);
        unset($client);

        return $filename;
    }

    public function getAllBooks(int $offset, int $limit): array
    {
        return $this->bookRepo->bookPagination($offset, $limit);
    }

    public function getBooksByCategory(string $category): array
    {
        return $this->bookRepo->getByCategory($category);
    }

    public function getBookBySlug(string $slug): Book|null
    {
        return $this->bookRepo->findOneBy(['slug' => $slug]);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getBooksCounter(): int
    {
        return $this->bookRepo->getBooksCounter();
    }
}