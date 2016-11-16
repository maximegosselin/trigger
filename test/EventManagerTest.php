<?php
declare(strict_types = 1);
namespace MaximeGosselin\Trigger\Test;

use MaximeGosselin\Trigger\EventInterface;
use MaximeGosselin\Trigger\EventManager;
use MaximeGosselin\Trigger\EventManagerInterface;
use PHPUnit\Framework\TestCase;

class EventManagerTest extends TestCase
{
    /**
     * @var EventManagerInterface
     */
    protected $manager;

    /**
     * @var TestEventListener
     */
    protected $listener1;

    /**
     * @var TestEventListener
     */
    protected $listener2;

    /**
     * @var TestEventListener
     */
    protected $listener3;

    public function setUp()
    {
        $this->manager = new EventManager();
        $this->listener1 = new TestEventListener();
        $this->listener2 = new TestEventListener();
        $this->listener3 = new TestEventListener();

        $this->assertFalse($this->listener1->isCalled());
        $this->assertFalse($this->listener2->isCalled());
        $this->assertFalse($this->listener3->isCalled());
    }

    public function testRegisterWithValidEventAndCallback()
    {
        $this->manager->on('foo', function ($event) {
        });
        $this->manager->on('foo', function (EventInterface $event) {
        });
        $this->manager->on('foo', function (EventInterface $event, $match) {
        });
        $this->manager->on('foo', function (EventInterface $event, string $match) {
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRegisterWithInvalidEventNameThrowsException1()
    {
        $this->manager->on('$%??&*', function (string $event) {
        });
    }

    /**
     * @expectedException \MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException
     */
    public function testRegisterWithInvalidCallbackThrowsException1()
    {
        $this->manager->on('foo', function () {
        });
    }

    /**
     * @expectedException \MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException
     */
    public function testRegisterWithInvalidCallbackThrowsException2()
    {
        $this->manager->on('foo', function (string $event) {
        });
    }

    /**
     * @expectedException \MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException
     */
    public function testRegisterWithInvalidCallbackThrowsException3()
    {
        $this->manager->on('foo', function ($event, int $match) {
        });
    }

    /**
     * @expectedException \MaximeGosselin\Trigger\Exception\InvalidCallbackSignatureException
     */
    public function testRegisterWithInvalidCallbackThrowsException4()
    {
        $this->manager->on('foo', function ($a, $b, $c) {
        });
    }

    public function testRegisteredListenersAreCalled()
    {
        $this->assertFalse($this->listener1->isCalled());
        $this->assertFalse($this->listener2->isCalled());
        $this->assertFalse($this->listener3->isCalled());

        $this->manager->on('foo', [
            $this->listener1,
            'handleEvent'
        ]);
        $this->manager->on('foo', [
            $this->listener2,
            'handleEvent'
        ]);
        $this->manager->on('bar', [
            $this->listener3,
            'handleEvent'
        ]);

        $this->manager->trigger('foo', [
            'foo' => 'hello',
            'bar' => 1
        ]);

        $this->assertTrue($this->listener1->isCalled());
        $this->assertTrue($this->listener2->isCalled());
        $this->assertFalse($this->listener3->isCalled());
    }

    public function testListenersAreCalledByPriority1()
    {
        $output = '';
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '1';
        }, 1);
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '2';
        }, 2);
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '3';
        }, 3);

        $this->manager->trigger('foo');
        $this->assertEquals($output, '321');
    }

    public function testListenersAreCalledByPriority2()
    {
        $output = '';
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '1';
        }, 3);
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '2';
        }, 2);
        $this->manager->on('foo', function ($event) use (&$output) {
            $output .= '3';
        }, 1);

        $this->manager->trigger('foo');
        $this->assertEquals($output, '123');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testTriggerWithInvalidEventNameThrowsException()
    {
        $this->manager->trigger('?&*?&?*');
    }

    public function testArgumentsArePassed()
    {
        $this->manager->on('foo', [
            $this->listener1,
            'handleEvent'
        ]);

        $this->manager->trigger('foo', [
            'foo' => 'hello',
            'bar' => 1
        ]);

        $this->assertEquals($this->listener1->getEvent()->getName(), 'foo');
        $this->assertEquals($this->listener1->getMatchPattern(), '/^foo$/');
        $this->assertArraySubset([
            'foo' => 'hello',
            'bar' => 1
        ], $this->listener1->getEvent()->getParams());
    }

    public function testRegularExpression()
    {
        $this->manager->on('/^foo.*$/', [
            $this->listener1,
            'handleEvent'
        ]);

        $this->manager->on('/baz/', [
            $this->listener2,
            'handleEvent'
        ]);

        $this->manager->on('/.*/', [
            $this->listener3,
            'handleEvent'
        ]);

        $this->manager->trigger('foo.bar');

        $this->assertTrue($this->listener1->isCalled());
        $this->assertFalse($this->listener2->isCalled());
        $this->assertTrue($this->listener3->isCalled());
    }
}
