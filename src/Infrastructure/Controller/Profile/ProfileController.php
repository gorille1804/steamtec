<?php

namespace Infrastructure\Controller\Profile;

use Domain\User\Factory\UserFactory;
use Domain\User\UseCase\UpdateUserUseCaseInterface;
use Infrastructure\Form\Profile\ProfileFormType ;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/dashboard')]
class ProfileController extends AbstractController
{
    public function __construct(
      private readonly  UpdateUserUseCaseInterface $useCase
    ){}
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request)
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser();
        $formRequest = UserFactory::makeFromUser($user->getUser());

        $form = $this->createForm(ProfileFormType::class, $formRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->useCase->__invoke($user->getUser()->getId(), $formRequest);
        }
        
        return $this->render('admin/profile/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}