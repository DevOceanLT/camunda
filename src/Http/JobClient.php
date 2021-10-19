<?php

namespace DevOceanLT\Camunda\Http;

class JobClient extends CamundaClient
{
    public static function all(): array
    {
        $response = self::make()->get("job");

        return $response->json();
    }
}
