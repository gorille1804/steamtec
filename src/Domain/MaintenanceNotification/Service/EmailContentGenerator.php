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
    public function generateMaintenanceEmailContent(MaintenanceNotificationModel $notification, array $hoursRanges): string
    {
        $machineInfo = $notification->machine->machine->nom . ' (ID : ' . $notification->machine->machine->numeroIdentification . ')';
        $timeReached = $notification->hours;
        $rangeDescription = $hoursRanges ? "entre {$hoursRanges['start']} et {$hoursRanges['end']} heures" : "";
        
        return <<<EMAIL
        Objet : Notification de maintenance - {$machineInfo}

        Cher client,

        Nous vous informons que vous avez atteint un palier d'entretien pour votre machine {$machineInfo}. 
        Afin de garantir la continuité de son bon fonctionnement, nous vous invitons à consulter le tableau ci-joint et à prendre les mesures nécessaires.

        Ceci est une notification automatique indiquant que votre machine {$machineInfo} a atteint {$timeReached} heures de fonctionnement et nécessite une maintenance programmée {$rangeDescription}.

        Veuillez contacter notre service après-vente afin de planifier cette maintenance importante.

        Nous restons à votre disposition pour toute question ou précision supplémentaire.

        Cordialement,  
        L'équipe de maintenance STEAMTECH
        EMAIL;
    }
}
