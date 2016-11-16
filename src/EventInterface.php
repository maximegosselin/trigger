<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger;

use InvalidArgumentException;

/**
 * Represents an immutable event.
 */
interface EventInterface
{
    /**
     * @return string
     */
    public function getName():string;

    /**
     * @return array
     */
    public function getParams():array;

    /**
     * @param string $name
     * @return mixed
     * @throws InvalidArgumentException if the parameter does not exist.
     */
    public function getParam(string $name);

    /**
     * @param string $name
     * @return EventInterface
     */
    public function withName(string $name):EventInterface;

    /**
     * @param array $params
     * @return EventInterface
     */
    public function withParams(array $params):EventInterface;
}
