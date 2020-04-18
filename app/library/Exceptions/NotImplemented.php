<?php
namespace Library\Exceptions;

use Exception;

class NotImplemented extends Exception
{
	public $httpErrorCode = 501;
	public $httpErrorType = 'Not Implemented';
}
