<?php

namespace App\Enums;

enum MemberType: string
{
    case Legal = 'legal';
    case Technical = 'technical';
    case Financial = 'financial';
    
    public function label(): string
    {
        return match($this) {
            self::Legal => 'عضو قانوني',
            self::Technical => 'عضو فني',
            self::Financial => 'عضو مالي',
        };
    }
}
