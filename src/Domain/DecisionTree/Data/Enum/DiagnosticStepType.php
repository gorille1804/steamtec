<?php

namespace Domain\DecisionTree\Data\Enum;

enum DiagnosticStepType: string
{
    case SYMPTOM = 'symptom';
    case CHECK = 'check';
    case ACTION = 'action';
}
