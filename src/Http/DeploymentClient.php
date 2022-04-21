<?php

declare(strict_types=1);

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\Deployment;
use DevOceanLT\Camunda\Exceptions\ParseException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class DeploymentClient extends CamundaClient
{
    public static function create(string $name, string|array $bpmnFiles): Deployment
    {
        $formData = [
            'deployment-name' => $name,
            'deployment-source' => sprintf('%s (%s)', config('app.name'), config('app.env')),
            'deploy-changed-only' => 'true',
            'enable-duplicate-filtering' => 'true'
        ];

        if (config('services.camunda.tenant_id')) {
            $formData['tenant-id'] = config('services.camunda.tenant_id');
        }

        $request = self::make();
        foreach ((array) $bpmnFiles as $bpmn) {
            $filename = pathinfo($bpmn)['basename'];
            $request->attach($filename, file_get_contents($bpmn), $filename);
        }

        $response = $request->post('deployment/create', $formData);

        if ($response->status() === 400) {
            throw new ParseException($response->json('message'));
        }

        return new Deployment($response->json());
    }

    public static function find(string $id): Deployment
    {
        $response = self::make()->get("deployment/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new Deployment($response->json());
    }

    public static function get(array $parameters = []): array
    {
        $response = self::make()->get('deployment', $parameters);
        $result = [];
        foreach ($response->json() as $data) {
            $result[] = new Deployment($data);
        }

        return $result;
    }

    public static function truncate(bool $cascade = false): void
    {
        $deployments = self::get();
        foreach ($deployments as $deployment) {
            self::delete($deployment->id, $cascade);
        }
    }

    public static function delete(string $id, bool $cascade = false): bool
    {
        $cascadeFlag = $cascade ? 'cascade=true' : '';
        $response = self::make()->delete("deployment/{$id}?".$cascadeFlag);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->status() === 204;
    }
}
