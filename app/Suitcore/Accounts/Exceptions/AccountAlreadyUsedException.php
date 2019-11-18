<?php 

namespace Suitcore\Accounts\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountAlreadyUsedException extends HttpException {
    const STATUS_CODE = 406;

    public function __construct($message = null, \Exception $previous = null, array $headers = array(), $code = 0)
    {
        if(!$message)
        {
            $message = 'Account already connected to other user';
        }
        parent::__construct(self::STATUS_CODE, $message, $previous, $headers, $code);
    }
}
