<?php

namespace Domain\Machine\UseCase;

use Domain\Document\Data\Model\Document;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Exception\MachineNotFoundException;
use Domain\Machine\Factory\MachineFactory;
use Domain\Machine\Gateway\MachineRepositoryInterface;
use Domain\Document\UseCase\DeleteDocumentUseCaseInterface;
class UpdateMachineUseCase implements UpdateMachineUseCaseInterface
{
    public function __construct(
        private readonly MachineRepositoryInterface $repository,
        private readonly DeleteDocumentUseCaseInterface $deleteDocumentUseCase
    ){}

    public function __invoke(MachineId $id, UpdateMachineRequest $request,  Document $document = null): Machine
    {
        $machine = $this->repository->findByid($id);
        if(!$machine){
            throw new MachineNotFoundException('Machine not found');
        }
        $ficheTechnique = $machine->getFicheTechnique();	
        $machine = MachineFactory::update($machine, $request, $document ?? $ficheTechnique);
        $machine =  $this->repository->save($machine);
       
        if($document && $ficheTechnique->id !== $document->id){
           $this->deleteDocumentUseCase->__invoke($ficheTechnique);
        }

        return $machine;
    }
}