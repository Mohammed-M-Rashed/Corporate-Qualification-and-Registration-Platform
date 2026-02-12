<?php

namespace App\Enums;

enum RequestActionType: string
{
    case InitialApproval = 'initial_approval';
    case InitialRejection = 'initial_rejection';
    case FinalApproval = 'final_approval';
    case FinalRejection = 'final_rejection';
}

