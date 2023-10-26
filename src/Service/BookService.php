<?php

namespace App\Service;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Category;
use App\Repository\BookRepository;
use DateTime;
use Exception;

class BookService
{
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
            $book->setThumbnailUrl($bookData['thumbnailUrl']);
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

}