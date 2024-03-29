<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index() :Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }
}