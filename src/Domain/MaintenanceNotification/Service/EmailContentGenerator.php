<?php

namespace Domain\MaintenanceNotification\Service;

use Domain\MaintenanceNotification\Data\Model\MaintenanceNotification as MaintenanceNotificationModel;

class EmailContentGenerator
{
    /**
     * Generate email content for maintenance notification
     *
     * @param MaintenanceNotificationModel $notification
     * @return string
     */
    public function generateMaintenanceEmailContent(MaintenanceNotificationModel $notification, array $hoursRanges): string
    {
        $machineInfo = $notification->machine->machine->nom . ' (ID: ' . $notification->machine->machine->numeroIdentification . ')';
        $timeRached = $notification->hours;
        $rangeDescription = $hoursRanges ? "between {$hoursRanges['start']} and {$hoursRanges['end']} hours" : "";
        
        return <<<EMAIL
        Subject: Maintenance Notification - {$machineInfo}

        Dear Customer,

        This is an automated notification that your machine {$machineInfo} has reached {$timeRached} hours of operation and requires scheduled maintenance {$rangeDescription}.

        Please contact our service department to schedule this important maintenance.

        Thank you,
        STEAMTECH Maintenance Team
        EMAIL;
    }
    
}