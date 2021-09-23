<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\Task;
use DevOceanLT\Camunda\Dto\Variable;
use DevOceanLT\Camunda\Dto\Casters\VariablesCaster;
use DevOceanLT\Camunda\Exceptions\CamundaException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class JobClient extends CamundaClient
{
    public static function all(): array
    {
        $response = self::make()->get("job");

        return $response->json();
    }
}
