<?php

namespace Infrastructure\Controller\Security;

use Domain\User\UseCase\ForgotPasswordUseCaseInterface;
use Infrastructure\Form\Security\ForgotPasswordFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ForgotPasswordController extends AbstractController
{

    public function __construct(
        private readonly ForgotPasswordUseCaseInterface $useCase,
        private readonly TranslatorInterface $translator,
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
            $this->addFlash('success', $this->translator->trans('users.messages.send_mail_succes'));
            return $this->redirectToRoute('app_forgot_password');	
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