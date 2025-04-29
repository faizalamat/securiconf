<?php

namespace App\Enums;

enum Nationality: string
{
    case MALAYSIAN = 'Malaysian';
    case OTHERS = 'Non-Malaysian';
    

    public function getIcon()
    {
        return match ($this) {
            self::MALAYSIAN => 'heroicon-o-flag',
            self::OTHERS => 'heroicon-o-flag',
        };
    }
}