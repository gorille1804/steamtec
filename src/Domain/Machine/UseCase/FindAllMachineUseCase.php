<?php

namespace Domain\Machine\UseCase;

use Domain\Machine\Gateway\MachineRepositoryInterface;


class FindAllMachineUseCase implements FindAllMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
    ){}

    public function __invoke(int $page = 1, int $limit = 10): array
    {	
        return $this->repository->getAll($page, $limit);
    }   

    public function getTotalMachines(): int
    {
        return $this->repository->getTotalMachines();
    }

    public function getAllMachinesRegistrationData(): array
    {
        $machineData =  $this->repository->getAllMachinesRegistrationData();
        $dates = [];
        $machineCounts = [];
        $formattedDates = [];
        
        $cumulativeCount = 0;
        $cumulativeData = [];
        
        foreach ($machineData as $data) {
            $dates[] = $data['date'];
            $machineCounts[] = $data['machineCount'];
            $dateObj = new \DateTime($data['date']);
            $formattedDates[] = $dateObj->format('d M');
            $cumulativeCount += (int)$data['machineCount'];
            $cumulativeData[] = $cumulativeCount;
        }
        
        return [
            'dates' => json_encode($dates),
            'formattedDates' => json_encode($formattedDates),
            'machineCounts' => json_encode($machineCounts),
            'cumulativeData' => json_encode($cumulativeData),
        ];
    }
}