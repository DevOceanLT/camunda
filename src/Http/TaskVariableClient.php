<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\Variable;

class TaskVariableClient extends CamundaClient
{
    /**
     * @param  array  $params
     *
     * @return TaskVariable[]
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function get(string $taskId): array
    {
        $variables = self::make()->get("task/$taskId/variables")->json();

        return collect($variables)->mapWithKeys(
            fn ($data, $name) => [$name => new Variable(name: $name, value: $data['value'], type: $data['type'])]
        )->toArray();
    }
}