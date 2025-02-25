<?php

namespace Domain\Machine\Factory;

use Domain\Machine\Data\Contract\CreateMachineRequest;
use Domain\Machine\Data\Model\Machine;
use Domain\Machine\Data\ObjectValue\MachineId;
use Domain\Machine\Data\Contract\UpdateMachineRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MachineFactory
{
    /**
     * Crée une nouvelle instance de Machine à partir de la requête de création.
     *
     * @param CreateMachineRequest $request
     * @return Machine
     */
    public static function make(CreateMachineRequest $request): Machine
    {
        $pathFile = self::getPathFile($request->ficheTechnique);
  
        // Créer une nouvelle machine avec un nouvel ID généré
        return new Machine(
            MachineId::make(), // ID généré
            $request->numeroIdentification,
            $request->nom,
            $request->marque,
            $request->seuilMaintenance,
            $pathFile,
            new \DateTimeImmutable(),
            null
        );
    }

     /**
     * Met à jour une machine existante avec de nouvelles données.
     *
     * @param Machine $machine
     * @param UpdateMachineRequest $request
     * @return Machine
     */
    public static function update(Machine $machine, UpdateMachineRequest $request): Machine
    {
        $machine->numeroIdentification = $request->numeroIdentification;
        $machine->nom = $request->nom;
        $machine->marque = $request->marque;
        $machine->seuilMaintenance = $request->seuilMaintenance;
        
        if ($request->ficheTechnique) {
            self::deleteFile($machine->ficheTechnique);
            $machine->ficheTechnique = self::getPathFile($request->ficheTechnique);
        }
         $machine->updatedAt = new \DateTimeImmutable();
        return $machine;
    }

     /**
     * Crée une instance d'UpdateMachineRequest à partir de la machine existante.
     *
     * @param Machine $machine
     * @return UpdateMachineRequest
     */
    public static function makeFromMachine(Machine $machine): UpdateMachineRequest
    {
        $formRequest = new UpdateMachineRequest();
        $formRequest->numeroIdentification = $machine->numeroIdentification;
        $formRequest->nom = $machine->nom;
        $formRequest->marque = $machine->marque;
        $formRequest->seuilMaintenance = $machine->seuilMaintenance;
        $formRequest->ficheTechnique=self::getFilecontent($machine->ficheTechnique);
        return $formRequest;	
    }

    /**
     * Gère l'upload du fichier et renvoie le chemin relatif.
     *
     * @param UploadedFile|null $file
     * @return string|null
     */
    public static function getPathFile(?UploadedFile $file): ?string
    {
        if (!$file) {
            return null;
        }

        $destination = __DIR__ . '/../../../public/uploads'; // Accès direct car pas de `$this->getParameter()`
        $newFilename = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($destination, $newFilename);
            return "/uploads/" . $newFilename;
        } catch (\Exception $e) {
            return null;
        }
    }


    /**
     * Récupère le contenu du fichier à partir du chemin et retourne un objet UploadedFile.
     *
     * @param string|null $path
     * @return UploadedFile|null
     */
    public static function getFilecontent(?string $path): ?UploadedFile
    {
        if (!$path) {
            return null;
        }
        $absolutePath = __DIR__ . '/../../../public' . $path;
        if (!file_exists($absolutePath)) {
            return null;
        }
        return new UploadedFile(
            $absolutePath, 
            basename($absolutePath),
            mime_content_type($absolutePath) ?: 'application/octet-stream', 
            null, 
            true 
        );
    }


    public static function deleteFile(?string $path): void
    {
        if ($path) {
            $absolutePath = __DIR__ . '/../../../public' . $path;
            if (file_exists($absolutePath)) {
                unlink($absolutePath); // Supprime le fichier
            }
        }
    }  

}