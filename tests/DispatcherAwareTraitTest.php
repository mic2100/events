<?php

namespace Mic2100EventsTests;

use Mic2100\Events\Dispatcher;
use Mic2100\Events\DispatcherAwareTrait;
use PHPUnit\Framework\TestCase;

class DispatcherAwareTraitTest extends TestCase
{
    use DispatcherAwareTrait;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new Dispatcher;
    }

    /**
     * @dataProvider dataInvalidDispatcherTypes
     *
     * @param $dispatcher
     */
    public function testSetDispatcherInvalidDispatcherExpectError($dispatcher)
    {
        $this->expectException('TypeError');
        $this->setDispatcher($dispatcher);
    }

    public function testGetterAndSetterSuccess()
    {
        $this->setDispatcher($this->dispatcher);
        $dispatcher = $this->getDispatcher();
        $this->assertSame($this->dispatcher, $dispatcher);
    }

    public function dataInvalidDispatcherTypes() : array
    {
        return [
            [1],
            ['a'],
            [true],
            [['a']],
            [new \stdClass],
            [new \Exception],
            [null],
        ];
    }
}