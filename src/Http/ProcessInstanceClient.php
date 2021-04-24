<?php

declare(strict_types=1);

namespace Laravolt\Camunda\Http;

use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Arr;
use Laravolt\Camunda\Dto\ProcessInstance;
use Laravolt\Camunda\Dto\Task;
use Laravolt\Camunda\Exceptions\ObjectNotFoundException;

class ProcessInstanceClient extends CamundaClient
{
    public static function find(string $id): ProcessInstance
    {
        $response = self::make()->get("process-instance/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new ProcessInstance($response->json());
    }

    public static function tasks(string $processInstanceId): array
    {
        $tasks = self::make()->get("task/?processInstanceId=$processInstanceId")->json();

        $data = [];
        foreach ($tasks as $task) {
            $data[] = new Task($task);
        }

        return $data;
    }

    public static function delete(string $id): bool
    {
        return self::make()->delete("process-instance/$id")->status() === 204;
    }
}