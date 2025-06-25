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
        $duration = (float) $request->duration;
        $surface = (float) $request->surface;
        $rendement = $duration > 0 ? $surface / $duration : 0;
        
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
            number_format($rendement, 2, '.', ''),
            $request->surfaceTypes,
            $request->materials,
            $request->encrassementLevel,
            $request->vetusteLevel,
            $request->commentaire ?? ''
        );
   }
}