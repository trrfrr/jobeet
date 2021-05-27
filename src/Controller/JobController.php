<?php

namespace App\Controller;

use App\Form\JobType;
use App\Repository\CategoryRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
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
     * @Route("/job/create", name="job.create",methods={"GET", "POST"})
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
            return $this->redirectToRoute('job.preview', ['token' => $job->getToken()]);
        }
        return $this->render('job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/job/{token}/edit", name="job.edit",methods={"GET", "POST"},requirements={"token" = "\w+"})
     */
    public function edit(Request $request, Jobs $jobs, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $oldFile= $jobs->getLogo();
        $form = $this->createForm(JobType::class, $jobs);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logo')->getData();
            if (isset($logoFile)) {
                if ($logoFile instanceof UploadedFile) {
                    $fileName = $fileUploader->upload($logoFile);
                    $jobs->setLogo($fileName);
                }
            }else{
                $jobs->setLogo($oldFile);
            }
            $em->flush();

            return $this->redirectToRoute('job.preview', ['token' => $jobs->getToken()]);

        }
        return $this->render('job/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/job/{id}", name="job.show", methods="GET", requirements={"id" = "\d+"})
     */
    public function show(Jobs $job): Response
    {
        return $this->render('job/show.html.twig', [
            'controller_name' => 'JobController',
            'job' => $job,
        ]);
    }

    /**
     * @Route("/job/{token}", name="job.preview", methods="GET", requirements={"token" = "\w+"})
     */
    public function preview(Jobs $job): Response
    {
        return $this->render('job/show.html.twig', [
            'controller_name' => 'JobController',
            'job' => $job,
            'hasControlAccess' => true,
        ]);
    }
}
