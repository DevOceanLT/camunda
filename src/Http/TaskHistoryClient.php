<?php

namespace DevOceanLT\Camunda\Http;

use Illuminate\Support\Arr;
use DevOceanLT\Camunda\Dto\TaskHistory;
use DevOceanLT\Camunda\Exceptions\CamundaException;
use DevOceanLT\Camunda\Exceptions\ObjectNotFoundException;

class TaskHistoryClient extends CamundaClient
{
    public static function find(string $id): TaskHistory
    {
        $response = self::make()->get("history/task?taskId=$id");

        if ($response->status() === 200) {
            if (empty($response->json())) {
                throw new ObjectNotFoundException(sprintf('Cannot find task history with ID = %s', $id));
            }

            return new TaskHistory(Arr::first($response->json()));
        }

        throw new CamundaException($response->json('message'));
    }

    /**
     * @param  string  $processInstanceId
     *
     * @return TaskHistory[]
     * @throws \Spatie\DataTransferObject\Exceptions\UnknownProperties
     */
    public static function getByProcessInstanceId(string $processInstanceId): array
    {
        $response = self::make()
            ->get(
                'history/task',
                [
                    'processInstanceId' => $processInstanceId,
                    'finished' => true,
                ]
            );

        if ($response->successful()) {
            $data = collect();
            foreach ($response->json() as $task) {
                $data->push(new TaskHistory($task));
            }

            return $data->sortBy('endTime')->toArray();
        }

        return [];
    }
}
