<?php
namespace Library\Exceptions;

use Exception;

class UnsupportedMediaType extends Exception
{
	public $httpErrorCode = 415;
	public $httpErrorType = 'Unsupported Media Type';
}
