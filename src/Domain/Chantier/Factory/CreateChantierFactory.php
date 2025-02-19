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
        return new Chantier(
            ChantierId::make(),
            $request->name,
            $request->description,
            $user,
            $request->hours,
            new \DateTimeImmutable(),
            null
        );
   }
}