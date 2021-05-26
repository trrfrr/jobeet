<?php

namespace App\Controller;

use App\Form\JobType;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Jobs;

class JobController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findWithActiveJobs();
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/job/create", name="job.create")
     */
    public function create(Request $request, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $job = new Jobs();
        $form = $this->createForm(JobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $logoFile = $form->get('logo')->getData();
            if ($logoFile instanceof UploadedFile) {
                $fileName = $fileUploader->upload($logoFile);

                $job->setLogo($fileName);
            }

            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}", name="job.show")
     */
    public function show(Jobs $job): Response
    {
        return $this->render('job/show.html.twig', [
            'controller_name' => 'JobController',
            'job' => $job,
        ]);
    }
}
