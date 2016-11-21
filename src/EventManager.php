<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException;
use ReflectionFunction;
use ReflectionMethod;
use RegexIterator;

class EventManager implements EventManagerInterface
{
    /**
     * @var array
     */
    private $listeners = [];

    public function on(string $match, callable $callback, int $priority = 0)
    {
        if (!$this->isValidEventName($match) && !$this->isValidRegEx($match)) {
            $message = 'Expected valid event name or regular expression, got "%s"';
            throw new InvalidArgumentException(sprintf($message, $match));
        }
        if (!$this->isValidListenerCallbackSignature($callback)) {
            throw new InvalidCallbackSignatureException();
        }
        $key = $this->isValidRegEx($match) ? $match : '/^' . preg_quote($match) . '$/';

        if (!isset($this->listeners[$key][$priority])) {
            $this->listeners[$key][$priority] = [];
        }
        $this->listeners[$key][$priority][] = $callback;
        krsort($this->listeners[$key]);
    }

    private function isValidEventName(string $event):bool
    {
        return preg_match('/^[A-Za-z0-9_\.]*$/', $event) == 1;
    }

    private function isValidRegEx(string $regex):bool
    {
        try {
            new RegexIterator(new ArrayIterator(), $regex);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    private function isValidListenerCallbackSignature(callable $callback):bool
    {
        if (is_array($callback)) {
            $function = new ReflectionMethod($callback[0], $callback[1]);
        } elseif (is_object($callback)) {
            $function = new ReflectionMethod($callback, '__invoke');
        } else {
            $function = new ReflectionFunction($callback);
        }
        $params = $function->getParameters();

        if (count($params) > 2) {
            return false;
        }
        if (!isset($params[0])) {
            return false;
        }
        if ($params[0]->getType() && (string)$params[0]->getType() != EventInterface::class) {
            return false;
        }
        if (isset($params[1]) && $params[1]->getType() && (string)$params[1]->getType() != 'string') {
            return false;
        }

        return true;
    }

    public function trigger(string $event, array $params = [])
    {
        if (!$this->isValidEventName($event)) {
            $message = <<<EOD
Event name contains invalid characters. It must match against regular expression /^[A-Za-z0-9_\.]*$/
EOD;
            throw new InvalidArgumentException(sprintf($message, $event));
        }

        foreach ($this->listeners as $match => $listeners) {
            if (preg_match($match, $event)) {
                foreach ($listeners as $priority => $callbacks) {
                    foreach ($callbacks as $callback) {
                        call_user_func_array($callback, [
                            new Event($event, $params),
                            $match
                        ]);
                    }
                }
            }
        }
    }
}
