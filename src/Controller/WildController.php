<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\Type\ProgramSearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WildController
 * @package App\Controller
 */
Class WildController extends AbstractController
{

    public function showByProgram($program) {
        return $program->getSeasons();
    }

    public function showBySeason($season) {
        return $season->getEpisodes();
    }

    public function getAllPrograms() {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $programs;
    }

    public function getAllCategories(){
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $categories;
    }
    public function add($object) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($object);
        $entityManager->flush();
    }
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/wild", name="wild_index")
     * @param $request
     * @return Response A response instance
     */
    public function index(Request $request) :Response
    {

        $programs = $this->getAllPrograms();
        $categories =$this->getAllCategories();
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category, array(
            'method' => 'put'
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $data = $form->getData();
            $this->add($data);
        }
        return $this->render(
            'wild/index.html.twig',
            [
                'programs' => $programs,
                'form' => $form->createView(),
                'categories' => $categories
                ]
        );
    }

    /**
     * Getting a program with a formatted slug for title
     *
     * @param string $slug The slugger
     * @Route("/wild/show/{slug<^[a-z0-9-]+$>}", defaults={"slug" = null}, name="wild_show")
     * @return Response
     */
    public function show(?string $slug):Response
    {

        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find a program in program\'s table.');
        }

        $slug = preg_replace(
            '/-/',
            ' ', ucwords(trim(strip_tags($slug)), "-")
        );

        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with '.$slug.' title, found in program\'s table.'
            );
        }

        $seasons = $this->showByProgram($program);
        $programs = $this->getAllPrograms();
        $categories =$this->getAllCategories();
        return $this->render('wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
            'seasons' => $seasons,
            'programs' => $programs,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/wild/season/{id}-{season}", name="wild_season")
     * @param Season $season
     * @param Program $id
     * @return Response
     */
    public function season(Program $id, Season $season):Response {
        $episodes = $this->showBySeason($season);
        $programs = $this->getAllPrograms();
        $categories =$this->getAllCategories();
        return $this->render('wild/season.html.twig', ['episodes' => $episodes,
            'program' => $id, 'programs' => $programs,'categories' => $categories]);
    }
}
