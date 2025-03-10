<?php

namespace Infrastructure\Controller\Dashboard;

use Infrastructure\Symfony\Services\MultiplyRolesExpression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Domain\User\Data\Enum\RoleEnum;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Domain\User\UseCase\FindAllUserUseCaseInterface;
use Domain\Machine\UseCase\FindAllMachineUseCaseInterface;
use Domain\ParcMachine\UseCase\FindAllParcMachineByUserUseCase;
use Domain\ParcMachine\UseCase\FindAllParcMachineByUserUseCaseInterface;
use Infrastructure\Symfony\Security\SymfonyUserAdapter;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly FindAllUserUseCaseInterface $findAllUserUseCase,
        private readonly FindAllMachineUseCaseInterface $findAllMachineByUserUseCase,
        private readonly FindAllParcMachineByUserUseCaseInterface $findAllParcMachineByUserUseCase
    ){}

    #[Route('/dashboard', name: 'app_dashboard', methods: ['GET'])]
    
    #[IsGranted(New MultiplyRolesExpression(RoleEnum::ADMIN, RoleEnum::USER))]
    public function index()
    {
        $dahsboradData = [];
        if($this->isGranted('ROLE_ADMIN')){
            $usersCount = $this->findAllUserUseCase->getTotalUsers();
            $machinesCount = $this->findAllMachineByUserUseCase->getTotalMachines();
            $userChart = $this->findAllUserUseCase->getAllUsersRegistrationData();
            $machineChart = $this->findAllMachineByUserUseCase->getAllMachinesRegistrationData();
            
            $dahsboradData = [
                'usersCount' => $usersCount,
                'machinesCount' => $machinesCount,
                'userChart' => $userChart,
                'machineChart' => $machineChart,
            ];
        }

        if($this->isGranted('ROLE_USER')){
            /** @var SymfonyUserAdapter $user */
            $user = $this->getUser();
            $user = $user->getUser();

            $parcsCount = $this->findAllParcMachineByUserUseCase->getTotalCount($user->id);
            $parcsMachines = $this->findAllParcMachineByUserUseCase->__invoke($user);
            $parcChart = $this->findAllParcMachineByUserUseCase->getUsersParcRegistrationData($user->id);

            $dahsboradData = [
                ...$dahsboradData,
                'parcsCount' => $parcsCount,
                'parcs' => $parcsMachines,
                'parcChart' => $parcChart,
            ];
        }



        return $this->render('admin/dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'dahsboradData' => $dahsboradData,
        ]);
    }
}