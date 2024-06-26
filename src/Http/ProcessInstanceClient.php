<?php

declare(strict_types=1);

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\Variable;
use DevOceanLT\Camunda\Dto\ProcessInstance;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class ProcessInstanceClient extends CamundaClient
{
    public static function get(array $parameters = []): array
    {
        $instances = [];
        foreach (self::make()->get('process-instance', $parameters)->json() as $res) {
            $instances[] = new ProcessInstance($res);
        }

        return $instances;
    }

    public static function post(array $parameters = []): array
    {
        $instances = [];
        foreach (self::make()->post('process-instance', $parameters)->json() as $res) {
            $instances[] = new ProcessInstance($res);
        }

        return $instances;
    }

    public static function find(string $id): ProcessInstance
    {
        $response = self::make()->get("process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new ProcessInstance($response->json());
    }

    public static function variables(string $id): array
    {
        $variables = self::make()->get("process-instance/$id/variables")->json();

        return collect($variables)->mapWithKeys(
            fn ($data, $name) => [$name => new Variable(name: $name, value: $data['value'], type: $data['type'])]
        )->toArray();
    }

    public static function delete(string $id): bool
    {
        return self::make()->delete("process-instance/$id")->status() === 204;
    }
}
