<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger\Test;

use MaximeGosselin\Trigger\EventInterface;

class TestEventListener
{
    /**
     * @var bool
     */
    private $isCalled = false;

    /**
     * @var float
     */
    private $microtime;

    /**
     * @var EventInterface
     */
    private $event;

    /**
     * @var string
     */
    private $match;

    public function handleEvent(EventInterface $event, string $match)
    {
        $this->event = $event;
        $this->match = $match;
        $this->isCalled = true;
        $this->microtime = microtime(true);
    }

    public function __invoke(EventInterface $event, string $match)
    {
        $this->event = $event;
        $this->match = $match;
        $this->isCalled = true;
        $this->microtime = microtime(true);
    }

    public function isCalled():bool
    {
        return $this->isCalled;
    }

    public function wasCalledOn():float
    {
        return $this->microtime;
    }

    public function getEvent():EventInterface
    {
        return $this->event;
    }

    public function getMatchPattern():string
    {
        return $this->match;
    }

    public function reset()
    {
        $this->event = null;
        $this->match = null;
        $this->isCalled = false;
        $this->microtime = 0;
    }
}
