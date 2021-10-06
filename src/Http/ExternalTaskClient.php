<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Dto\ExternalTask;
use DevOceanLT\Camunda\Exceptions\CamundaException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;
use DevOceanLT\Camunda\Exceptions\InvalidArgumentException;

class ExternalTaskClient extends CamundaClient
{
    public static function find(string $id): ExternalTask
    {
        $response = self::make()->get("external-task/$id");

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return new ExternalTask($response->json());
    }

    /**
     * @return ExternalTask[]
     */
    public static function all(): array
    {
        $response = self::make()->get("external-task");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $externalTask) {
                $data[] = new ExternalTask($externalTask);
            }
        }

        return $data;
    }

    /**
     * @param  string  $processInstanceId
     *
     * @return ExternalTask[]
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function getByProcessInstanceId(string $id): array
    {
        $response = self::make()->get("external-task?processInstanceId=$id");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $externalTask) {
                $data[] = new ExternalTask($externalTask);
            }
        }

        return $data;
    }

    public static function fetchAndLock(array $parameters = []): array
    {
        // workerId must be set
        if (empty($parameters['workerId'])) {
            throw new InvalidArgumentException('Cannot fetchAndLock external task without providing workerId');
        }

        $response = self::make()->post('external-task/fetchAndLock', $parameters);

        if ($response->successful()) {
            $data = [];
            foreach ($response->json() as $externalTask) {
                $data[] = new ExternalTask($externalTask);
            }

            return $data;
        }

        throw new InvalidArgumentException($response->body());
    }

    public static function complete(string $id, array $parameters = []): bool
    {
        $response = self::make()->post(
            "external-task/$id/complete",
            $parameters
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function unlock(string $id): bool
    {
        $response = self::make()->post("external-task/$id/unlock");

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function failure(string $id, array $parameters = []): bool
    {
        $response = self::make()->post(
            "external-task/$id/failure",
            $parameters
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }
}
