<?php

namespace App\Interface;

interface SaveEntityInterface
{
    public function save(object $object, bool $flush = false);
}