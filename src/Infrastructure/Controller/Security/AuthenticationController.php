<?php
namespace Infrastructure\Controller\Security;

use Domain\User\UseCase\AuthenticationUseCaseInterface;
use Domain\User\UseCase\LogoutUseCaseInterface;
use Infrastructure\Form\Security\AuthenticationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUseCaseInterface $useCase,
        private readonly LogoutUseCaseInterface $logoutUseCase
    ){}
    
    #[Route('/login', name: 'app_security', methods: ['GET', 'POST'])]
    public function index(Request $request)
    {
        $form = $this->createForm(AuthenticationFormType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $result = $this->useCase->__invoke($form->getData(), $request);
                return $result ?? $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }
        
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $form->getErrors()
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Request $request)
    {
        $this->logoutUseCase->__invoke($request);
        return $this->redirectToRoute('app_security');    
    }
}