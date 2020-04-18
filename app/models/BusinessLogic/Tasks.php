<?php
namespace Model\BusinessLogic;

use Phalcon\Http\Response;
use Phalcon\Mvc\User\Component;

use Library\Exceptions\BadRequest;
use Library\Exceptions\UnsupportedMediaType;
use Library\Exceptions\NotFound;
use Library\Exceptions\NotImplemented;

use Model\Entity\Tasks as TasksModel;

class Tasks extends Component
{
    /**
     * Create a new task
     * 
     * @return nothing
     */
    public function create()
    {
        $jsonInput = $this->request->getRawBody();
        if(!is_null($inputData = json_decode($jsonInput, true)))
        {
            $this->validate($inputData, 'create');
            $task = new TasksModel(
                [
                    'title' => $inputData['title'],
                    'description' => $inputData['description'],
                    'parentId' => $inputData['parentId'] ?? null
                ]
            );
            if($task->create())
            {
                $this->returnTaskInfo($task);
            }
            else
            {
                $errorMessages = implode(", ", $task->getMessages());
                throw new BadRequest($errorMessages);
            }
        }
        else
        {
            throw new UnsupportedMediaType('Create requests should send data in JSON format');
        }
    }

    public function read(...$params)
    {
        $outputTasks = [];
        $taskIds = array_values(array_filter($params, function ($item) { return is_numeric($item); }));
        if(!empty($taskIds))
        {
            $tasks = TasksModel::find(
                [
                    'conditions' => 'id IN ({taskIds:array})',
                    'bind' => [
                        'taskIds' => $taskIds
                    ]
                ]
            );
            foreach($tasks as $task)
            {
                $outputTasks[ $task->getId() ] = $task->toArray();
            }
        }
        $response = new Response();
        $response->setStatusCode(200, 'OK');
        $response->setJsonContent($outputTasks);
        $response->send();
    }

    /**
     * Update the task with the given data
     *
     * @param int $taskId the task ID to update
     * 
     * @return nothing
     */
    public function update(int $taskId)
    {
        $jsonInput = $this->request->getRawBody();
        if(!is_null($inputData = json_decode($jsonInput, true)))
        {
            $this->validate($inputData, 'update');
            $task = TasksModel::findFirstById($taskId);
            $saveData = [
                'title' => $inputData['title'] ?? $task->getTitle(),
                'description' => $inputData['description'] ?? $task->getDescription(),
                'parentId' => $inputData['parentId'] ?? $task->getParentId()
            ];
            if($task->save($saveData))
            {
                $this->returnTaskInfo($task);
            }
            else
            {
                $errorMessages = implode(", ", $task->getMessages());
                throw new BadRequest($errorMessages);
            }
        }
        else
        {
            throw new UnsupportedMediaType('Update requests should send data in JSON format');
        }
    }

    public function delete(int $taskId)
    {
        $task = TasksModel::findFirstById($taskId);
        if($task)
        {
            $task->setParentId(null);
            if($task->save())
            {
                $response = new Response();
                $response->setStatusCode(200, 'OK');
                $response->send();
            }
            else
            {
                $errorMessages = implode(", ", $task->getMessages());
                throw new BadRequest($errorMessages);
            }
        }
        else
        {
            throw new NotFound("The task to delete does not exist");
        }
    }

    /**
     * Validate that the action has the required fields
     * 
     * @param array  $data   the data from the user
     * @param string $action the action requested
     * 
     * @return nothing
     */
    private function validate(array $data, string $action)
    {
        $requiredFields = [
            'create' => ['title', 'description'],
        ];
        if(array_key_exists($action, $requiredFields))
        {
            foreach($requiredFields[ $action ] as $field)
            {
                if(!array_key_exists($field, $data))
                {
                    throw new BadRequest(ucwords($action) . " requests require '" . $field . "' field");
                }
            }
        }
    }

    /**
     * Sends the full task info in an HTTP response
     * 
     * @param TasksModel $task the task
     * 
     * @return nothing
     */
    private function returnTaskInfo(TasksModel $task)
    {
        $response = new Response();
        $response->setStatusCode(200, 'OK');
        $response->setJsonContent($task->toArray());
        $response->send();
    }
}