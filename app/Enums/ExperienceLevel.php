<?php

namespace App\Enums;

enum ExperienceLevel: string
{
    case ZeroToThree = '0-3';
    case FourToTen = '4-10';
    case MoreThanTen = 'more_than_10';
}

