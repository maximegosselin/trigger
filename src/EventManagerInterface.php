<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger;

use InvalidArgumentException;
use MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException;

interface EventManagerInterface
{
    /**
     * @param string $match An event name or a regular expression.
     * @param callable $callback
     * @param int $priority
     * @throws InvalidArgumentException If $match is not a valid event name or regular expression.
     * @throws InvalidCallbackSignatureException If $callback does not have a valid signature.
     */
    public function on(string $match, callable $callback, int $priority = 0);

    /**
     * @param string $event
     * @param array $arguments
     * @throws InvalidArgumentException If $event contains invalid characters.
     */
    public function trigger(string $event, array $arguments = []);
}
