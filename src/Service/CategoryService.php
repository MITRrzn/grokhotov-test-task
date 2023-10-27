<?php

namespace App\Service;

use App\Entity\Category;
use App\Helper\SlugHelper;
use App\Interface\ServiceInterface;
use App\Repository\CategoryRepository;
use Exception;

class CategoryService implements ServiceInterface
{
    public function __construct(private readonly CategoryRepository $categoryRepo)
    {
    }

    /**
     * @throws Exception
     */
    public function findOrCreate(string $name): Category
    {
        $category = $this->categoryRepo->findOneBy(['name' => $name]);
        if ($category instanceof Category) {
            return $category;
        }

        $newCategory = new Category();
        $newCategory
            ->setName($name)
            ->setSlug(SlugHelper::slugify($name))
        ;
        $this->categoryRepo->save($newCategory, true);

        return $newCategory;
    }

    public function getAllCategories(): array
    {
        return $this->categoryRepo->findAll();
    }
}