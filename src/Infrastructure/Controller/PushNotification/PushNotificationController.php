<?php

namespace Infrastructure\Controller\PushNotification;

use Domain\PushNotification\Data\Model\PushNotification;
use Domain\PushNotification\UseCase\CreatePushNotificationUseCaseInterface;
use Domain\PushNotification\UseCase\DeletePushNotificationUseCaseInterface;
use Domain\PushNotification\UseCase\FindAllPushNotificationByUserUseCaseInterface;
use Domain\PushNotification\UseCase\UpdateStatusPushNotificationUseCaseInterface;
use Domain\User\UseCase\FindUserByIdUseCaseInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/dashboard')]
class PushNotificationController extends AbstractController
{
    public function __construct(
        private readonly  CreatePushNotificationUseCaseInterface $createUseCase,
        private readonly DeletePushNotificationUseCaseInterface $deletePushNotificationUseCase,
        private readonly FindAllPushNotificationByUserUseCaseInterface $findAllByUserPushNotificationUseCase,
        private readonly TranslatorInterface $translator,
        private readonly FindUserByIdUseCaseInterface $findUserByIdUseCase,
        private readonly UpdateStatusPushNotificationUseCaseInterface $updateStatusPushNotificationUseCase,
    ){}

    #[Route('/pushNotifications', name: 'app_push_notification')]
    #[IsGranted('ROLE_USER')]
    public function index(): array
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser(); 
        $receiver= $user->getUser();
        $pushNotifications = $this->findAllByUserPushNotificationUseCase->__invoke($receiver);
        return  $pushNotifications;
    }


    #[Route('/pushNotifications/{pushNotification}/update', name: 'app_update_push_notification', methods:['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateStatusPushNotification(PushNotification $pushNotification): Response
    {
        try {
            $this->updateStatusPushNotificationUseCase->__invoke($pushNotification);
            $this->addFlash('success', $this->translator->trans('parc_machines.message.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('parc_machines.message.delete_error'));
        }
        return $this->redirectToRoute('app_parc_machines');
    }

    #[Route('/pushNotifications/{pushNotification}/delete', name: 'app_delete_push_notification', methods:['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deletePushNotification(PushNotification $pushNotification): Response
    {
        try {
            $this->deletePushNotificationUseCase->__invoke($pushNotification);
            $this->addFlash('success', $this->translator->trans('parc_machines.message.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('parc_machines.message.delete_error'));
        }
        return $this->redirectToRoute('app_parc_machines');
    }
}