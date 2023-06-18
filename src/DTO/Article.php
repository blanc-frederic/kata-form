<?php

declare(strict_types=1);

namespace App\DTO;

use DateTimeInterface;

class Article
{
    public function __construct(
        public string $title,
        public DateTimeInterface $date,
        public string $content
    ) {
    }
}
