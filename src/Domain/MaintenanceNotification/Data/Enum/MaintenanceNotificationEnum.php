<?php

namespace Domain\MaintenanceNotification\Data\Enum;

enum MaintenanceNotificationEnum: string
{
    case REGULAR_MAINTENANCE = 'maintenance_notification_regular';
    case TIMELY_MAINTENANCE = 'maintenance_notification_timely';
    case EMERGENCY_MAINTENANCE = 'maintenance_notification_emergency';
}