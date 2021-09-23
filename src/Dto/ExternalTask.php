<?php

namespace DevOceanLT\Camunda\Dto;

use Illuminate\Support\Carbon;
use DevOceanLT\Camunda\Dto\Casters\CarbonCaster;
use Spatie\DataTransferObject\Attributes\Strict;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Attributes\CastWith;

#[Strict]
class ExternalTask extends DataTransferObject
{
    public string $id;

    public string $activityId;

    public string $activityInstanceId;

    public string|null $errorMessage;

    public string|null $errorDetails;

    public string $executionId;

    #[CastWith(CarbonCaster::class)]
    public Carbon|null $lockExpirationTime;

    public string $processDefinitionId;

    public string $processDefinitionKey;

    public string|null $processDefinitionVersionTag;

    public string $processInstanceId;

    public string|null $tenantId;

    public array|null $variables;

    public int|null $retries;

    public bool $suspended;

    public string|null $workerId;

    public string $topicName;

    public string $priority;

    public string|null $businessKey;

    public array|null $extensionProperties;
}
