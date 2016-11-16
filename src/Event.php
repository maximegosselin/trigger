<?php
declare(strict_types = 1);

namespace MaximeGosselin\Trigger;

use InvalidArgumentException;

final class Event implements EventInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $params;

    public function __construct(string $name, array $params = [])
    {
        $this->name = $name;
        $this->params = $params;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getParams():array
    {
        return $this->params;
    }

    public function getParam(string $name)
    {
        if (!array_key_exists($name, $this->params)) {
            throw new InvalidArgumentException(sprintf("Parameter '%s' does not exist.", $name));
        }

        return $this->params[$name];
    }

    public function withName(string $name):EventInterface
    {
        $clone = clone $this;
        $clone->name = $name;

        return $clone;
    }

    public function withParams(array $params):EventInterface
    {
        $clone = clone $this;
        $clone->params = $params;

        return $clone;
    }
}
