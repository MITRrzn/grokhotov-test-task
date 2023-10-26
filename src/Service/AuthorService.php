<?php

namespace App\Service;

use App\Entity\Author;
use App\Interface\ServiceInterface;
use App\Repository\AuthorRepository;
use Exception;

class AuthorService implements ServiceInterface
{
    public function __construct(private readonly AuthorRepository $authorRepo)
    {
    }

    /**
     * @throws Exception
     */
    public function findOrCreate(string $name): Author
    {
        $author = $this->authorRepo->findOneBy(['name' => $name]);
        if ($author instanceof Author) {
            return $author;
        }

        $newAuthor = new Author();
        $newAuthor->setName($name);
        $this->authorRepo->save($newAuthor, true);

        return $newAuthor;
    }

}