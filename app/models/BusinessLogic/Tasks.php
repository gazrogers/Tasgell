<?php
namespace Model\BusinessLogic;

use Phalcon\Http\Response;
use Phalcon\Mvc\User\Component;

use Library\Exceptions\BadRequest;
use Library\Exceptions\UnsupportedMediaType;

use Model\Entity\Tasks as TasksModel;

class Tasks extends Component
{
    /**
     * Create a new task
     * 
     * @param string $jsonInput a JSON string containing the task data
     * 
     * @return nothing
     */
    public function create(string $jsonInput)
    {
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
                $response = new Response();
                $response->setStatusCode(200, 'OK');
                $response->setJsonContent(['id' => $task->getId()]);
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
            throw new UnsupportedMediaType('Create requests should send data in JSON format');
        }
    }

    /**
     * Update the task with the given data
     * 
     * @param string $jsonInput a JSON string containing the updated task data
     * 
     * @return nothing
     */
    public function update(string $jsonInput)
    {
        if(!is_null($inputData = json_decode($jsonInput, true)))
        {
            $this->validate($inputData, 'update');
            $task = TasksModel::findFirstById($inputData['id']);
            $saveData = [
                'title' => $inputData['title'] ?? $task->getTitle(),
                'description' => $inputData['description'] ?? $task->getDescription(),
                'parentId' => $inputData['parentId'] ?? $task->getParentId()
            ];
            if($task->save($saveData))
            {
                $response = new Response();
                $response->setStatusCode(200, 'OK');
                $response->setJsonContent(['id' => $task->getId()]);
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
            throw new UnsupportedMediaType('Update requests should send data in JSON format');
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
            'update' => ['id']
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
}