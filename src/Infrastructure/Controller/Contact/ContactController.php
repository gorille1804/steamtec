<?php

namespace Infrastructure\Controller\Contact;

use Domain\Contact\Data\Contract\ContactRequest;
use Domain\Contact\UseCase\SendContactMailUseCaseInterface;
use Infrastructure\Form\Contact\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class ContactController extends AbstractController
{

    public function __construct(
        private readonly SendContactMailUseCaseInterface $sendContactUseCase,
    ) {}

    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request)
    {
        $contactRequest = new ContactRequest();
        $form = $this->createForm(ContactType::class, $contactRequest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->sendContactUseCase->__invoke($contactRequest);
            $this->addFlash('success', 'Votre message a été envoyé.');
            return $this->redirectToRoute('app_contact');
        }
        return $this->render('client/contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
        return $this->render('client/contact/index.html.twig');
    }
}