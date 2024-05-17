<?php

namespace App\EventSubscriber;

use Twig\Environment;
use App\Repository\BookRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TwigEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Environment $twig,
        private BookRepository $bookRepository
    ) {
    }

    public function onControllerEvent(ControllerEvent $event): void
    {
        $this->twig->addGlobal('books', $this->bookRepository->findAll());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
