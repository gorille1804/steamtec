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
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/pushNotifications', name: 'app_push_notification', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function index(): JsonResponse
    {
        /** @var SymfonyUserAdapter $user */
        $user = $this->getUser(); 
        $receiver= $user->getUser();
        $pushNotifications = $this->findAllByUserPushNotificationUseCase->__invoke($receiver);
        return  $this->json($pushNotifications, 200);
    }


    #[Route('/pushNotifications/{pushNotification}/update', name: 'app_update_push_notification', methods:['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateStatusPushNotification(PushNotification $pushNotification): JsonResponse
    {
        try {
            $this->updateStatusPushNotificationUseCase->__invoke($pushNotification);
            return  $this->json(200);
        } catch (\Exception $e) {
            return  $this->json(400);
        }
    }

    #[Route('/pushNotifications/{pushNotification}/delete', name: 'app_delete_push_notification', methods:['POST'])]
    #[IsGranted('ROLE_USER')]
    public function deletePushNotification(PushNotification $pushNotification): void
    {
        try {
            $this->deletePushNotificationUseCase->__invoke($pushNotification);
            $this->addFlash('success', $this->translator->trans('push_notifications.message.delete_succes'));
        } catch (\Exception $e) {
            $this->addFlash('error', $this->translator->trans('push_notifications.message.delete_error'));
        }
    }
}