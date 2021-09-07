<?php

namespace DevOceanLT\Camunda\Dto\Casters;

use Illuminate\Support\Carbon;
use Spatie\DataTransferObject\Caster;

class CarbonCaster implements Caster
{
    public function cast(mixed $value): Carbon
    {
        return Carbon::parse($value);
    }
}
