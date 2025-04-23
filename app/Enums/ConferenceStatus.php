<?php

namespace App\Enums;

use App\Enums\Base\Enum;

enum ConferenceStatus: string
{
    case Draf = 'Draft';
    case Published = 'Published';
    case Archived = 'Archived';
}