<?php


namespace App\Listener;
// src/EventListener/ControllerListener.php

use App\Entity\Category;
use App\Entity\Program;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;

class ControllerListener
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(Environment $twig, EntityManager $manager, Security $security)
    {
        $this->twig = $twig;
        $this->manager = $manager;
        $this->security = $security;
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        $user = $this->security->getUser();
        $this->twig->addGlobal('programs', $programs);
        $this->twig->addGlobal('category', $categories);
    }
}