<?php

namespace App\Controller;

use App\Entity\UserMessage;
use App\Service\FeedbackService;
use Doctrine\ORM\Query\Parameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/feedback', name: 'feedback:')]
class FeedbackController extends AbstractController
{
    public function __construct(
        private readonly FeedbackService $feedbackService
    )
    {
    }

    /**
     * @throws \Exception
     */
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        $message = new UserMessage();

        $form = $this->createFormBuilder($message)
            ->add('email', EmailType::class,[
                'required' => true,
            ])
            ->add('name', TextType::class)
            ->add('message', TextareaType::class, [
                'required' => true
            ])
            ->add('phone', TelType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);
        $errors = $form->getErrors(true);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->feedbackService->saveUserMessage($message);
            return $this->redirectToRoute('feedback:success');
        }

        return $this->render('feedback/index.html.twig', [
            'form' => $form,
            'errors' => $errors
        ]);
    }
    #[Route('/success', name: 'success')]
    public function success(Request $request): Response
    {
        $referer = $request->headers->get('referer');
        $path = parse_url($referer, PHP_URL_PATH);
        if ($path !== '/feedback/') {
            return $this->redirectToRoute('home');
        }

        return $this->render('feedback/success.html.twig');
    }

}
