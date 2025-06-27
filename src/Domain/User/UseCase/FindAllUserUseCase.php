<?php

namespace Domain\User\UseCase;

use Domain\User\Gateway\UserRepositoryInterface;

class FindAllUserUseCase implements FindAllUserUseCaseInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $respository
    ){}
    public function __invoke(int $page = 1, int $limit = 10): array
    {
        return $this->respository->getAll($page, $limit);
    }

    public function getTotalUsers(): int
    {
        return $this->respository->getTotalUsers();
    }

    public function getAllUsersRegistrationData(): array
    {
        $userData =  $this->respository->getAllUsersRegistrationData();
        $dates = [];
        $userCounts = [];
        $formattedDates = [];
        
        $cumulativeCount = 0;
        $cumulativeData = [];
        
        foreach ($userData as $data) {
            $dates[] = $data['date'];
            $userCounts[] = $data['userCount'];
            $dateObj = new \DateTime($data['date']);
            $formattedDates[] = $dateObj->format('d M');
            $cumulativeCount += (int)$data['userCount'];
            $cumulativeData[] = $cumulativeCount;
        }
        
        return [
            'dates' => json_encode($dates),
            'formattedDates' => json_encode($formattedDates),
            'userCounts' => json_encode($userCounts),
            'cumulativeData' => json_encode($cumulativeData),
        ];
    }

    
}