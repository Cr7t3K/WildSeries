<?php

// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_ADMIN")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("admin/category/add", name="category_add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request) :Response
    {

        $categorys = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_add');
        }

        return $this->render(
            'category/_form-category.html.twig', [
                'categorys' => $categorys,
                'form' => $form->createView()
        ]);
    }


    public function allcategory(CategoryRepository $categorys)
    {

        return $this->render('category/_all_category.html.twig', [
            'categorys' => $categorys->findAll(),
        ]);
    }


    /**
     * @Route("/category/{name}", name="show_category_program")
     */
    public function showCategoryProgram(Category $category) :Response
    {
        return $this->render('category/show.html.twig', [
            'programs' => $category->getPrograms(),
        ]);
    }

}
