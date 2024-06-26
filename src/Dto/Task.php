<?php

namespace DevOceanLT\Camunda\Dto;

use Illuminate\Support\Carbon;
use DevOceanLT\Camunda\Dto\Casters\CarbonCaster;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

#[Strict]
class Task extends DataTransferObject
{
    public string $id;

    public string $name;

    public string|null $assignee;

    #[CastWith(CarbonCaster::class)]
    public Carbon $created;

    #[CastWith(CarbonCaster::class)]
    public Carbon|null $due;

    #[CastWith(CarbonCaster::class)]
    public Carbon|null $followUp;

    public string|null $delegationState;

    public string|null $description;

    public string $executionId;

    public string|null $owner;

    public string|null $parentTaskId;

    public string $priority;

    public string $processDefinitionId;

    public string $processInstanceId;

    public string $taskDefinitionKey;

    public string|null $caseExecutionId;

    public string|null $caseInstanceId;

    public string|null $caseDefinitionId;

    public bool $suspended;

    public string|null $formKey;

    public array|null $camundaFormRef;

    public string|null $tenantId;
}
