<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\Task;
use DevOceanLT\Camunda\Dto\Variable;
use DevOceanLT\Camunda\Dto\Casters\VariablesCaster;
use DevOceanLT\Camunda\Exceptions\CamundaException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class TaskClient extends CamundaClient
{
    public static function find(string $id): Task
    {
        $response = self::make()->get("task/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new Task($response->json());
    }

    /**
     * @return Task[]
     */
    public static function all(): array
    {
        $response = self::make()->get("task");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $task) {
                $data[] = new Task($task);
            }
        }

        return $data;
    }

    /**
     * @param  array  $params
     *
     * @return Task[]
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function get(array $params): array
    {
        $response = self::make()->post("task", $params);

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $task) {
                $data[] = new Task($task);
            }
        }

        return $data;
    }

    /**
     * @param  string  $id
     *
     * @return Task[]
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function getByProcessInstanceId(string $id): array
    {
        $response = self::make()->get("task?processInstanceId=$id");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $task) {
                $data[] = new Task($task);
            }
        }

        return $data;
    }

    public static function claim(string $id, string $userId): bool
    {
        $response = self::make()->post(
            "task/$id/claim",
            ['userId' => $userId]
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function unclaim(string $id): bool
    {
        $response = self::make()->post("task/$id/claim");

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function complete(string $id, array $variables = null): bool
    {
        $response = self::make()->post(
            "task/$id/complete",
            compact('variables')
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function submitForm(string $id, array $variables): bool
    {
        $response = self::make()->post(
            "task/$id/submit-form",
            compact('variables')
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function submitFormAndReturnVariables(string $id, array $variables): array
    {
        $response = self::make()->post(
            "task/$id/submit-form",
            ['variables' => $variables, 'withVariablesInReturn' => true]
        );

        if ($response->status() === 200) {
            return (new VariablesCaster(['array'], Variable::class))->cast($response->json());
        }

        throw new CamundaException($response->body(), $response->status());
    }
}
