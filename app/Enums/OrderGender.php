<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum OrderGender: string implements HasLabel
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function getLabel(): ?string
    {
        return str($this->value)->title();
    }
}
