<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedbackController extends AbstractController
{
    #[Route('/feedback', name: 'app_feedback')]
    public function index(): Response
    {

        return $this->json('feedback form will be here');
//        return $this->render('feedback/index.html.twig', [
//            'controller_name' => 'FeedbackController',
//        ]);
    }
}
