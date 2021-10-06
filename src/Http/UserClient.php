<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Exceptions\ForbiddenException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class UserClient extends CamundaClient
{
    public static function create(array $params): bool
    {
        $response = self::make()->post("user/create", $params);

        if ($response->status() === 403) {
            throw new ForbiddenException($response->json('message'));
        }

        return $response->status() === 204;
    }

    public static function update(string $id, array $params): bool
    {
        $response = self::make()->put("user/{$id}/profile", $params);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->status() === 204;
    }

    public static function updateCredentials(string $id, array $params): bool
    {
        $response = self::make()->put("user/{$id}/credentials", $params);

        if ($response->status() === 404) {
            throw new ObjectNotFoundException($response->json('message'));
        }

        return $response->status() === 204;
    }
}
