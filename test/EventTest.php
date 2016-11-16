<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger\Test;

use MaximeGosselin\Trigger\Event;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testGetters()
    {
        $name = 'foo';
        $params = [
            'bar',
            true,
            42
        ];
        $e = new Event($name, $params);

        $this->assertSame($e->getName(), $name);
        $this->assertSame($e->getParams(), $params);
        $this->assertSame($e->getParam('0'), 'bar');
        $this->assertSame($e->getParam('1'), true);
        $this->assertSame($e->getParam('2'), 42);

        $this->expectException(\InvalidArgumentException::class);
        $e->getParam('hello');
    }

    public function testImmutability()
    {
        $e1 = new Event('foo');

        $e2 = $e1->withName('bar');
        $this->assertNotSame($e1, $e2);

        $e3 = $e2->withParams([]);
        $this->assertNotSame($e2, $e3);
    }
}
