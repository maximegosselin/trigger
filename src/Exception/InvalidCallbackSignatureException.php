<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger\Exception;

use Exception;
use InvalidArgumentException;
use MaximeGosselin\Trigger\EventInterface;

class InvalidCallbackSignatureException extends InvalidArgumentException
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        $message = "Callback must have signature (%s \$event, string \$match)";
        $message = sprintf($message, EventInterface::class);

        parent::__construct($message, $code, $previous);
    }
}
