<?php

declare(strict_types=1);

namespace DevOceanLT\Camunda\Dto;

use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;
use DevOceanLT\Camunda\Dto\Casters\VariablesCaster;

#[Strict]
class ProcessInstance extends DataTransferObject
{
    public string $id;

    public string|null $tenantId;

    public string|null $businessKey;

    public array $links;

    public string $definitionId;

    public ?string $caseInstanceId;

    public bool $ended;

    public bool $suspended;

    /** @var \DevOceanLT\Camunda\Dto\Variable[]  */
    #[CastWith(VariablesCaster::class, Variable::class)]
    public array|null $variables;
}
