<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }


##[Route('/book/{slug}', name: 'book')]
    public function show(
        Book $book
    ) :Response {
        return $this->render('book/show.html.twig', [
            'book' => $book
        ]); 
    }

}
