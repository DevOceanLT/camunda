<?php

namespace DevOceanLT\Camunda\Http;

use DevOceanLT\Camunda\Exceptions\BadRequestException;

class MessageClient extends CamundaClient
{
    public static function message(string $messageName, string $businessKey, array $correlationKeys = null, array $processVariables = null): bool
    {
        $params = [
            'messageName' => $messageName,
            'businessKey' => $businessKey
        ];

        if ($correlationKeys) {
            $params['correlationKeys'] = $correlationKeys;
        }

        if ($processVariables) {
            $params['processVariables'] = $processVariables;
        }

        $response = self::make()->post("message", $params);

        if ($response->status() === 400) {
            throw new BadRequestException($response->json('message'));
        }

        return $response->status() === 204;
    }
}
