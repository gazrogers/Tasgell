<?php
namespace Library\Exceptions;

use Exception;

class BadRequest extends Exception
{
	public $httpErrorCode = 400;
	public $httpErrorType = 'Bad Request';
}
