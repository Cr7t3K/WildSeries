<?php
// src/Controller/WildController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


/**
 * Class WildController
 *
 * @package App\Controller
 * @Route("/wild", name="wild_")
 */
class WildController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index() : Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        if (!$programs) {
            throw $this->createNotFoundException(
                'No program found in program\'s table.'
            );
        }

        return $this->render(
            'wild/index.html.twig',
                ['programs' => $programs]
        );
    }

    /**
     * @Route("/show/{slug}",
     *     requirements={"slug"="[\s/\w\-]+"},
     *     defaults={"slug"="Aucune série sélectionnée, veuillez choisir une série"},
     *     name="show")
     * @param $slug
     * @return Response
     */
    public function show(?string $slug) : Response
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

        return $this->render(
            'wild/show.html.twig', [
            'program' => $program,
            'slug'  => $slug,
        ]);
    }

    /**
     * @Route("/category/{categoryName}",
     *     name="show_category")
     * @param string $categoryName
     * @return Response
     */
    public function showByCategory(string $categoryName) :Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No category with '.$categoryName.' name, found in category table.');
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name'=> $categoryName]);

        $programsByCategory = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['category' => $category],
                ['id' => 'desc'],
                3
            );

        return $this->render(
            'wild/category.html.twig', [
                'programsByCategory' => $programsByCategory,
                'currentCategory' => $categoryName
                ]
        );
    }

    /**
     * @Route("/program/{slug}",
     *     requirements={"slug"="[\s/\w\-]+"},
     *     defaults={"slug"="Aucune série sélectionné"},
     *     name="program")
     * @param $slug
     * @return Response
     */
    public function showByProgram(string $slug) : Response
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

        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(['program' => $program]);

        return $this->render(
            'wild/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons
        ]);
    }

    /**
     * @Route("/season/{id}",
     *     name="program_season")
     * @param $id
     * @return Response
     */
    public function showBySeason(int $id) :Response
    {
        if (!$id) {
            throw $this
                ->createNotFoundException('No id has been sent to find a season.');
        }
        $season = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findOneBy(['id' => $id]);

        $program = $season->getProgram();
        $episodes = $season->getEpisodes();

        return $this->render(
            'wild/show-season.html.twig', [
                'season' => $season,
                'program' => $program,
                'episodes' => $episodes
        ]);

    }

    /**
     * @Route("/episode/{id}",
     *     requirements={"id"="[/\d]+"},
     *     name="episode")
     * @param Episode $episode
     * @return Response
     */
    public function showEpisode(Episode $episode) :Response
    {
        $season = $episode->getSeason();
        $program = $season->getProgram();

        return $this->render(
            'wild/show-episode.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode
        ]);
    }

}
