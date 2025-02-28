<?php

namespace Infrastructure\Controller\Security;

use Domain\User\UseCase\ResetPasswordUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Infrastructure\Form\Security\ResetPasswordFormType;
class ResetPasswordController extends AbstractController
{

    public function __construct(
        private readonly ResetPasswordUseCaseInterface $useCase
    ){}

    #[Route('/reset-password/{token}', name: 'app_reset_password', methods: ['GET', 'POST'])]
    public function index(Request $request, string $token)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->useCase->__invoke($form->getData(), $token);	
                $this->addFlash('success', 'Votre mot de passe a ete mis a jour');
                return $this->redirectToRoute('app_security');
            } catch (\Throwable $th) {
                $this->addFlash('error', $th->getMessage());
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}