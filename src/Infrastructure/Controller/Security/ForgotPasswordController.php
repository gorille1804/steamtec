<?php

namespace Infrastructure\Controller\Security;

use Domain\User\UseCase\ForgotPasswordUseCaseInterface;
use Infrastructure\Form\Security\ForgotPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractController
{

    public function __construct(
        private readonly ForgotPasswordUseCaseInterface $useCase
    ){}

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function index(Request $request)
    {
        //remove flash message if existe
        $session = $request->getSession();
        $session->remove('error');
        $session->remove('success');

        $form = $this->createForm(ForgotPasswordFormType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
         try {
            $this->useCase->__invoke($form->getData());
            $this->addFlash('success', 'Un email de confirmation vous a ete envoye');
         } catch (\Throwable $th) {
            $this->addFlash('error', $th->getMessage());
         }
        }
        return $this->render('security/forgot_password.html.twig',[
            'form' => $form->createView(),
            'errors' => $form->getErrors()
        ]);
    }

}