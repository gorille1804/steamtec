<?php

namespace Domain\MaintenanceNotification\Data\Constant;

class MaintenanceNotification
{
    public const MINIMAL_HOURS = 50;
    public const MAXIMAL_HOURS = 3500;
    public const MINIMAL_TIMELY_HOURS = 700;
    public const MAXIMAL_TIMELY_HOURS = 3500;
    public const URL_FILE= "public/uploads/maintenance_machine/ELEC_ENTRETIEN_REGULIER&PONCTUEL.pdf";
    public const REGULAR_MAINTENANCE_RANGES = [
        ['start' => 50, 'end' => 100],
        ['start' => 100, 'end' => 200],
        ['start' => 200, 'end' => 300],
        ['start' => 300, 'end' => 400],
        ['start' => 400, 'end' => 500],
        ['start' => 500, 'end' => 600],
        ['start' => 600, 'end' => 700],
        ['start' => 700, 'end' => 800],
        ['start' => 800, 'end' => 900],
        ['start' => 900, 'end' => 1000],
        ['start' => 1000, 'end' => 1100],
        ['start' => 1100, 'end' => 1200],
        ['start' => 1200, 'end' => 1300],
        ['start' => 1300, 'end' => 1400],
        ['start' => 1400, 'end' => 1500],
        ['start' => 1500, 'end' => 1600],
        ['start' => 1600, 'end' => 1700],
        ['start' => 1700, 'end' => 1800],
        ['start' => 1800, 'end' => 1900],
        ['start' => 1900, 'end' => 2000],
        ['start' => 2000, 'end' => 2100],
        ['start' => 2100, 'end' => 2200],
        ['start' => 2200, 'end' => 2300],
        ['start' => 2300, 'end' => 2400],
        ['start' => 2400, 'end' => 2500],
        ['start' => 2500, 'end' => 2600],
        ['start' => 2600, 'end' => 2700],
        ['start' => 2700, 'end' => 2800],
        ['start' => 2800, 'end' => 2900],
        ['start' => 2900, 'end' => 3000],
        ['start' => 3000, 'end' => 3100],
        ['start' => 3100, 'end' => 3200],
        ['start' => 3200, 'end' => 3300],
        ['start' => 3300, 'end' => 3400],
        ['start' => 3400, 'end' => 3500]
    ];
    //public const TIMELY_MAINTENANCE_RANGES = []
    public const TIMELY_MAINTENANCE_RANGES = [
        ['start' => 700, 'end' => 1400],
        ['start' => 1400, 'end' => 2100],
        ['start' => 2100, 'end' => 2800],
        ['start' => 2800, 'end' => 3500],
    ];

    public const MAINTENANCE_THRESHOLDS = [
        700 => 1, 1400 => 2, 2100 => 3, 2800 => 4, 3500 => 5
    ];
}