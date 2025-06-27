<?php

namespace Infrastructure\Controller\Document;

use Domain\Document\Data\Model\Document;
use Domain\Document\UseCase\DownloadDocumentUseCaseInterface;
use Domain\Document\UseCase\ShowDocumentUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Domain\User\Data\Enum\RoleEnum;

# [Route('/document')]
class DocumentController extends AbstractController
{

    public function __construct(
        private readonly DownloadDocumentUseCaseInterface $downloadDocumentUseCase,
        private readonly ShowDocumentUseCaseInterface $showDocumentUseCase
    ) {}

    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    #[Route('/download/{document}', name: 'document_download', methods: ['GET'])]
    public function download(Request $request, Document $document): void
    {
        $this->downloadDocumentUseCase->__invoke($document);
    }

    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    #[Route('/show/{document}', name: 'document_show', methods: ['GET'])]
    public function show(Request $request, Document $document)
    {
        $this->showDocumentUseCase->__invoke($document);
    }
}