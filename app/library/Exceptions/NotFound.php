<?php
namespace Library\Exceptions;

use Exception;

class NotFound extends Exception
{
	public $httpErrorCode = 404;
	public $httpErrorType = 'Not Found';
}
