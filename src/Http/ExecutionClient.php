<?php

namespace DevOceanLT\Camunda\Http;

class ExecutionClient extends CamundaClient
{
    /**
     * @param string $id
     *
     * @return array
     */
    public static function getLocalVariables(string $id): array
    {
        $response = self::make()->get("execution/$id/localVariables");

        $data = [];
        if ($response->successful()) {
            foreach ($response->json() as $name => $variable) {
                $data[$name] = $variable;
            }
        }

        return $data;
    }
}
