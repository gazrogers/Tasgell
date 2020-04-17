<?php
namespace Model\BusinessLogic;

use Exception;
use ReflectionClass;
use Library\Exceptions\UnsupportedMediaType;
use Phalcon\Http\Response;

class ErrorHandler
{
	public function handle(Exception $exception)
	{
		$httpErrorCode = property_exists($exception, 'httpErrorCode') ? $exception->httpErrorCode : 500;
		$httpErrorType = property_exists($exception, 'httpErrorType') ? $exception->httpErrorType : 'Internal Server Error';
		$response = new Response();
		$response->setStatusCode($httpErrorCode, $httpErrorType);
		$response->setJsonContent(['error' => $exception->getMessage()]);
		$response->send();
	}
}