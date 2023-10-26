<?php

namespace App\Interface;

interface ServiceInterface
{
    public function findOrCreate(string $name);
}