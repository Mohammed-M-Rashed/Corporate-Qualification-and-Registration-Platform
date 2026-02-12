<?php

namespace App\Enums;

enum QualificationRequestStatus: string
{
    case New = 'new';
    case UnderReview = 'under_review';
    case Approved = 'approved';
    case Rejected = 'rejected';
}

