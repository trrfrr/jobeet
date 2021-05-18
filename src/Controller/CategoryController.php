<?php

namespace App\Controller;

use App\Entity\Category;

use App\Repository\JobsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category.show")
     */
    public function index(Category $category, PaginatorInterface $paginator ,Request $request,JobsRepository $jobsRepository): Response
    {
        $page = max(1, $request->query->getInt('page', 1));
        $jobs = $paginator->paginate(
            $jobsRepository->getPaginatedActiveJobsByCategoryQuery($category),
            $page, // page
            $this->getParameter('max_jobs_on_category')
        );
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
            'category' => $category,
            'jobs' => $jobs,
        ]);
    }
}
