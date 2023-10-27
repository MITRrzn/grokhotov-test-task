<?php

namespace App\Twig;

use App\Service\CategoryService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('categoriesList', [$this, 'getCategories']),
        ];
    }

    public function getCategories(): array
    {
        return $this->categoryService->getAllCategories();
    }
}