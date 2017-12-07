<?php

namespace Mic2100EventsTests;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractEventTest
 *
 * @category Events Testing
 * @package  Mic2100EventsTests
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
class AbstractEventTest extends TestCase
{
    /**
     * @dataProvider dataTestEventsThatThrowExpectedException
     *
     * @param string $eventClass
     * @param string $expectedException
     * @param string $exceptionMessage
     */
    public function testEventsThatThrowExpectedExceptions($eventClass, $expectedException, $exceptionMessage)
    {
        $this->expectException($expectedException);
        $this->expectExceptionMessage($exceptionMessage);
        (new $eventClass)->handle();
    }

    /**
     * @return array
     */
    public function dataTestEventsThatThrowExpectedException()
    {
        return [[
            'Mic2100EventsTests\Events\EmptyStringHandleConstantEvent',
            '\InvalidArgumentException',
            'Missing handle for event: Mic2100EventsTests\Events\EmptyStringHandleConstantEvent',
        ],[
            'Mic2100EventsTests\Events\ZeroIntegerHandleConstantEvent',
            '\InvalidArgumentException',
            'Missing handle for event: Mic2100EventsTests\Events\ZeroIntegerHandleConstantEvent',
        ],[
            'Mic2100EventsTests\Events\FalseBooleanHandleConstantEvent',
            '\InvalidArgumentException',
            'Missing handle for event: Mic2100EventsTests\Events\FalseBooleanHandleConstantEvent',
        ],[
            'Mic2100EventsTests\Events\EmptyArrayHandleConstantEvent',
            '\InvalidArgumentException',
            'Missing handle for event: Mic2100EventsTests\Events\EmptyArrayHandleConstantEvent',
        ],[
            'Mic2100EventsTests\Events\MissingHandleEvent',
            '\RuntimeException',
            'Missing handle functionality for event: Mic2100EventsTests\Events\MissingHandleEvent',
        ]];
    }
}
