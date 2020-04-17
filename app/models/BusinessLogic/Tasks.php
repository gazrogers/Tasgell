<?php
namespace Model\BusinessLogic;

use Phalcon\Http\Response;
use Phalcon\Mvc\User\Component;

use Library\Exceptions\BadRequest;
use Library\Exceptions\UnsupportedMediaType;

use Model\Entity\Tasks as TasksModel;

class Tasks extends Component
{
	public function create(string $jsonInput)
	{
		if(!is_null($inputData = json_decode($jsonInput, true)))
		{
			$task = new TasksModel(
				[
					'title' => $inputData['title'],
					'description' => $inputData['description'],
					'parentId' => $inputData['parentId'] ?? null
				]
			);
			$response = new Response();
			if($task->create())
			{
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
}