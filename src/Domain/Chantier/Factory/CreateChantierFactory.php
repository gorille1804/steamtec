<?php

namespace Domain\Chantier\Factory;

use Domain\Chantier\Data\Contract\CreateChantierRequest;
use Domain\Chantier\Data\Model\Chantier\Chantier;
use Domain\Chantier\Data\ObjectValue\ChantierId;
use Domain\User\Data\Model\User;

class CreateChantierFactory
{
   public static function make(CreateChantierRequest $request, User $user):Chantier
   {
        // Calcul automatique du rendement
        $rendement = $request->duration > 0 ? $request->surface / $request->duration : 0;
        
        return new Chantier(
            ChantierId::make(),
            $request->name,
            $request->description,
            $user,
            new \DateTimeImmutable(),
            null,
            $request->machineSerialNumber,
            $request->chantierDate,
            $request->surface,
            $request->duration,
            $rendement,
            $request->surfaceTypes,
            $request->materials,
            $request->encrassementLevel,
            $request->vetusteLevel,
            $request->commentaire
        );
   }
}