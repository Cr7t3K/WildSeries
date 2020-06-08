<?php

// src/Controller/ActorController.php
namespace App\Controller;


use App\Entity\Actor;
use App\Repository\ActorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor")
 */
class ActorController extends AbstractController
{

    /**
     * @Route("/", name="_index")
     * @param ActorRepository $actorRepository
     * @return Response
     */
    public function index(ActorRepository $actorRepository) :Response
    {
        return $this->render(
            "actor/index.html.twig", [
                'actors' => $actorRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/show/{slug}", name="_show")
     * @param Actor $actor
     * @return Response
     */
    public function show(Actor $actor) :Response
    {
        return $this->render(
            "actor/show.html.twig", [
                "actor" => $actor
                ]
        );

    }

}