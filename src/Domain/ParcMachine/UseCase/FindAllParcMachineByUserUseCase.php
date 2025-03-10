<?php
Namespace Domain\ParcMachine\UseCase;

use Domain\ParcMachine\Gateway\ParcMachineRepositoryInterface;
use Domain\User\Data\Model\User;
use Domain\User\Data\ObjectValue\UserId;

class FindAllParcMachineByUserUseCase implements FindAllParcMachineByUserUseCaseInterface
{
    public function __construct(
        private readonly ParcMachineRepositoryInterface $repository,
    )
    {}
    public function __invoke(User $user): array
    {
        $parcMachines= $this->repository->findAllByUser($user);
        return $parcMachines;
    }

    public function getTotalCount(UserId $userId): int
    {
        return $this->repository->getTotalCount($userId);
    }

    public function getUsersParcRegistrationData(UserId $userId): array
    {
        $parcData =  $this->repository->getUsersParcRegistrationData($userId);
        $dates = [];
        $parcCounts = [];
        $formattedDates = [];
        
        $cumulativeCount = 0;
        $cumulativeData = [];
        
        foreach ($parcData as $data) {
            $dates[] = $data['date'];
            $parcCounts[] = $data['parcCount'];
            $dateObj = new \DateTime($data['date']);
            $formattedDates[] = $dateObj->format('d M');
            $cumulativeCount += (int)$data['parcCount'];
            $cumulativeData[] = $cumulativeCount;
        }
        
        return [
            'dates' => json_encode($dates),
            'formattedDates' => json_encode($formattedDates),
            'parcCounts' => json_encode($parcCounts),
            'cumulativeData' => json_encode($cumulativeData),
        ];
    }
}