<?php

namespace DevOceanLT\Camunda\Dto;

use Illuminate\Support\Carbon;
use DevOceanLT\Camunda\Dto\Casters\CarbonCaster;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

#[Strict]
class Deployment extends DataTransferObject
{
    public string $id;

    public string|null $tenantId;

    public ?string $name;

    public ?string $source;

    #[CastWith(CarbonCaster::class)]
    public Carbon $deploymentTime;

    public ?array $processDefinitions;

    public ?array $links;

    public ?array $deployedProcessDefinitions;

    public ?array $deployedCaseDefinitions;

    public ?array $deployedDecisionDefinitions;

    public ?array $deployedDecisionRequirementsDefinitions;
}
