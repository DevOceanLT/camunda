<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Exceptions\ForbiddenException;

class GroupClient extends CamundaClient
{
    public static function create(array $params): bool
    {
        $response = self::make()->post("group/create", $params);

        if ($response->status() === 403) {
            throw new ForbiddenException($response->json('message'));
        }

        return $response->status() === 204;
    }

    public static function addMember(string $groupId, string $userId): bool
    {
        $response = self::make()->put("group/$groupId/members/$userId");

        if ($response->status() === 403) {
            throw new ForbiddenException($response->json('message'));
        }

        return $response->status() === 204;
    }

    public static function removeMember(string $groupId, string $userId): bool
    {
        $response = self::make()->delete("group/$groupId/members/$userId");

        if ($response->status() === 403) {
            throw new ForbiddenException($response->json('message'));
        }

        return $response->status() === 204;
    }
}
