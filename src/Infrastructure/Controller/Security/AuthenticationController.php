<?php
namespace Infrastructure\Controller\Security;

use Domain\User\UseCase\AuthenticationUseCaseInterface;
use Domain\User\UseCase\LogoutUseCaseInterface;
use Infrastructure\Form\Security\AuthenticationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\SecurityRequestAttributes;

class AuthenticationController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationUseCaseInterface $useCase,
        private readonly LogoutUseCaseInterface $logoutUseCase
    ){}
    
    #[Route('/login', name: 'app_security', methods: ['GET', 'POST'])]
    public function index(Request $request)
    {
        $error = $request->getSession()->get('auth_error');
        if ($error) {
            $this->addFlash('error', $error);
            $request->getSession()->remove('auth_error');
        }


       $form = $this->createForm(AuthenticationFormType::class);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->useCase->__invoke($form->getData(), $request);
                return $this->redirectToRoute('app_dashboard');
            } catch (\throwable $e) {
                $this->addFlash('error', $e->getMessage());
            } catch (\Throwable $e) {
                $this->addFlash('error', 'An unexpected error occurred. Please try again.');
            }
        }
        
        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'errors' => $form->getErrors(),
            'last_username' => $request->getSession()->get(SecurityRequestAttributes::LAST_USERNAME)
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout(Request $request)
    {
        $this->logoutUseCase->__invoke($request);
        return $this->redirectToRoute('app_security');    
    }
}