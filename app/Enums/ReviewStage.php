<?php

namespace App\Enums;

enum ReviewStage: string
{
    case Legal = 'legal';
    case Technical = 'technical';
    case Financial = 'financial';
    case Chairman = 'chairman';
    case Completed = 'completed';
    
    public function label(): string
    {
        return match($this) {
            self::Legal => 'قانوني',
            self::Technical => 'فني',
            self::Financial => 'مالي',
            self::Chairman => 'رئيس اللجنة',
            self::Completed => 'مكتمل',
        };
    }
}
