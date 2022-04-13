<?php

declare(strict_types=1);

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Exceptions\CamundaException;

class ModificationClient extends CamundaClient
{
    public static function execute(array $parameters): bool
    {
        $response = self::make()->post(
            "modification/execute",
            $parameters
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }

    public static function executeAsync(array $parameters): bool
    {
        $response = self::make()->post(
            "modification/executeAsync",
            $parameters
        );

        if ($response->status() === 204) {
            return true;
        }

        throw new CamundaException($response->body(), $response->status());
    }
}
