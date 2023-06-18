<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Article;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/', 'homepage')]
    public function edit(Request $request): Response
    {
        $article = new Article(
            'Exemple',
            new DateTimeImmutable(),
            'Ceci est un exemple'
        );

        return $this->render('exemple.html.twig', [
            'article' => $article,
        ]);
    }
}
