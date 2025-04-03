<?php

namespace Domain\MaintenanceNotification\Service;

use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification as MaintenanceNotificationModel;

class EmailContentGenerator
{
    /**
     * Générer le contenu de l'email pour la notification de maintenance
     *
     * @param MaintenanceNotificationModel $notification
     * @param array $hoursRanges
     * @return string
     */
    public function generateMaintenanceEmailContent(MaintenanceNotificationModel $notification, array $hoursRanges  = []): string
    {
        $machineName = $notification->machine->machine->nom;
        $machineId = $notification->machine->machine->numeroIdentification;
        $timeReached = $notification->hours;

        return <<<EMAIL

        Nous vous informons que vous avez atteint un palier d'entretien de {$timeReached} heures pour votre machine {$machineName} (ID : {$machineId}).
        Afin de garantir la continuité de son bon fonctionnement, nous vous invitons à consulter le tableau ci-joint et à prendre les mesures nécessaires.

        Nous restons à votre disposition pour toute question ou précision supplémentaire.

        Cordialement,  
        L'équipe ENTECH
        EMAIL;
    }
}
