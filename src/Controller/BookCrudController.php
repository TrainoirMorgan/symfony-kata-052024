<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class BookCrudController extends AbstractController
{
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
        return $this->render('book_crud/index.html.twig', [
            'books' => $bookRepository->findAll(),
        ]);
    }

    #[IsGranted('ROLE_USER')]

    #[Route('/book/new', name: 'app_book_crud_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_crud/new.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[Route('/book/{slug}', name: 'app_book_crud_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book_crud/show.html.twig', [
            'book' => $book,
        ]);
    }

   
    #[IsGranted('ROLE_USER')]
    #[Route('/book/{slug}/edit', name: 'app_book_crud_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_book_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('book_crud/edit.html.twig', [
            'book' => $book,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/book/{slug}', name: 'app_book_crud_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_book_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
