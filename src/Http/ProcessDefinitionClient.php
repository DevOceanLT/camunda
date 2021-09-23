<?php

declare(strict_types=1);

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\ProcessInstance;
use DevOceanLT\Camunda\Dto\ProcessDefinition;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;
use DevOceanLT\Camunda\Exceptions\InvalidArgumentException;

class ProcessDefinitionClient extends CamundaClient
{
    public static function start($params): ProcessInstance
    {
        $variables = $params['variables'] ?? null;
        $businessKey = $params['businessKey'] ?? null;

        $payload = [
            'withVariablesInReturn' => true
        ];
        if ($variables) {
            $payload['variables'] = $variables;
        }
        if ($businessKey) {
            $payload['businessKey'] = $businessKey;
        }

        $key = $params['key'];
        $path = "process-definition/key/{$key}/start";
        $response = self::make()->post($path, $payload);

        if ($response->successful()) {
            return new ProcessInstance($response->json());
        }

        throw new InvalidArgumentException($response->body());
    }

    public static function xml(...$args): string
    {
        $path = self::makeIdentifierPath(path: 'process-definition/{identifier}/xml', args: $args);

        return self::make()->get($path)->json('bpmn20Xml');
    }

    public static function get(array $parameters = []): array
    {
        $processDefinition = [];
        foreach (self::make()->get('process-definition', $parameters)->json() as $res) {
            $processDefinition[] = new ProcessDefinition($res);
        }

        return $processDefinition;
    }

    public static function find(...$args): ProcessDefinition
    {
        $response = self::make()->get(self::makeIdentifierPath('process-definition/{identifier}', $args));

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new ProcessDefinition($response->json());
    }
}
