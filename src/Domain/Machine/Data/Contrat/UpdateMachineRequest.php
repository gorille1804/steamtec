<?php

namespace Domain\Machine\Data\Contract;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateMachineRequest
{
    public string $numeroIdentification;
    public string $nom;
    public string $marque;
    public int $seuilMaintenance;
    public ?UploadedFile $ficheTechnique=null;
}