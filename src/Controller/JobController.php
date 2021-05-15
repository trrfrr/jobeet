<?php

namespace App\Controller;

use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JobsRepository;
use App\Entity\Jobs;

class JobController extends AbstractController
{
    /**
     * @Route("/", name="hompage")
     */
    public function index(JobsRepository $jobsRepository): Response
    {
        $jobs = $jobsRepository->findAll();
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'jobs' => $jobs,
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



