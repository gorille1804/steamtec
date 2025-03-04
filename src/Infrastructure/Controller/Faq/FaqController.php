<?php

namespace Infrastructure\Controller\Faq;

use Domain\Faq\Data\Contract\CreateFaqRequest;
use Domain\Faq\Data\Contract\UpdateFaqRequest;
use Domain\Faq\Data\Model\Faq;
use Domain\Faq\Factory\FaqFactory;
use Domain\Faq\UseCase\CreateFaqUseCaseInterface;
use Domain\Faq\UseCase\FindAllFaqsUseCaseInterface;
use Domain\Faq\UseCase\UpdateFaqUseCaseInterface;
use Domain\Faq\UseCase\DeleteFaqUseCaseInterface;
use Infrastructure\Form\Faq\FaqFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard')]
class FaqController extends AbstractController
{
    public function __construct(
        private readonly FindAllFaqsUseCaseInterface $findAllFaqsUseCase,
        private readonly CreateFaqUseCaseInterface $createFaqUseCase,
        private readonly UpdateFaqUseCaseInterface $updateFaqUseCase,
        private readonly DeleteFaqUseCaseInterface $deleteFaqUseCase,
        private readonly TranslatorInterface $translator,
    ) {}

    #[Route('/faqs', name: 'app_faqs', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 10;
        $faqs = $this->findAllFaqsUseCase->__invoke($page, $limit);
        $totalFaqs = $this->findAllFaqsUseCase->count();
        $maxPages = ceil($totalFaqs / $limit);
        return $this->render('admin/faq/index.html.twig', [
            'faqs' => $faqs,
            'maxPages'=> $maxPages,
        ]);
    }

    #[Route('/faqs/create', name:'app_faqs_create', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $createFaqRequest = new CreateFaqRequest();
        $form = $this->createForm(FaqFormType::class, $createFaqRequest, [
            'is_edit' => false,
            'data_class' => CreateFaqRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->createFaqUseCase->__invoke($createFaqRequest);
                $this->addFlash('success', $this->translator->trans('FAQs.messages.create_succes'));
                return $this->redirectToRoute('app_faqs');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('FAQs.messages.create_error'));
            }
        }
      
        return $this->render('admin/faq/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => false,
        ]);
    }

    #[Route('/faqs/{faq}/edit', name:'app_faqs_update', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function update(Request $request, Faq $faq): Response
    {
        $updateFaqRequest = FaqFactory::makeFromFaq($faq);
        $form = $this->createForm(FaqFormType::class, $updateFaqRequest, [
            'is_edit' => true,
            'data_class' => UpdateFaqRequest::class,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->updateFaqUseCase->__invoke($faq->id, $updateFaqRequest);
                $this->addFlash('success', $this->translator->trans('FAQs.messages.update_succes'));
                return $this->redirectToRoute('app_faqs');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('FAQs.messages.update_error'));
            }
        }

        return $this->render('admin/faq/create_update.html.twig', [
            'form' => $form->createView(),
            'is_edit' => true,
            'faq' => $faq,
        ]);
    }

    #[Route('/faqs/{faq}/delete', name:'app_faqs_delete', methods:['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Faq $faq): Response
    {
        if ($this->isCsrfTokenValid('delete'.$faq->id, $request->request->get('_token'))) {
            try {
                $this->deleteFaqUseCase->__invoke($faq);
                $this->addFlash('success', $this->translator->trans('FAQs.messages.delete_succes'));
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('FAQs.messages.delete_error'));
            }
        }
        
        return $this->redirectToRoute('app_faqs');
    }

    #[Route('/faqs/{faq}/status', name:'app_faqs_update_status', methods:['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function updateStatus(Request $request, Faq $faq): Response
    {
        try {
            $this->updateFaqUseCase->updateStatus($faq);
            $this->addFlash('success', $this->translator->trans('FAQs.messages.update_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('FAQs.messages.update_error'));
        }

        return $this->redirectToRoute('app_faqs');
    }
}