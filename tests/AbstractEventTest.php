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

    public function dataTestEventsThatThrowExpectedException()
    {
        return [
            [
                'Mic2100EventsTests\Events\MissingHandleConstantEvent',
                '\InvalidArgumentException',
                'Missing handle for event: Mic2100EventsTests\Events\MissingHandleConstantEvent',
            ],
            [
                'Mic2100EventsTests\Events\MissingHandleEvent',
                '\RuntimeException',
                'Missing handle functionality for event: Mic2100EventsTests\Events\MissingHandleEvent',
            ],
        ];
    }
}
