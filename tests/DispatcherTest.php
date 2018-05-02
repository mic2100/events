<?php

namespace Mic2100EventsTests;

use Mic2100\Events\Dispatcher;
use Mic2100EventsTests\Events\FileCreationEvent;
use Mic2100EventsTests\Events\HandleMethodReturnsFalseEvent;
use Mic2100EventsTests\Events\HandleMethodReturnsTrueEvent;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    private $testFiles;

    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new Dispatcher;

        $this->testFiles = [
            [
                'destination' => __DIR__ . '/../testfile1',
                'contents' => md5(__DIR__ . '/../testfile1'),
                'handle' => 'create.testfile1',
            ],[
                'destination' => __DIR__ . '/../testfile2',
                'contents' => md5(__DIR__ . '/../testfile2'),
                'handle' => 'create.testfile2',
            ],[
                'destination' => __DIR__ . '/../testfile3',
                'contents' => md5(__DIR__ . '/../testfile3'),
                'handle' => 'create.testfile3',
            ],[
                'destination' => __DIR__ . '/../testfile4',
                'contents' => md5(__DIR__ . '/../testfile4'),
                'handle' => 'create.testfile4',
            ],
        ];

        foreach ($this->testFiles as $testFile) {
            $this->dispatcher->addEvent(
                new FileCreationEvent([
                    'destination' => $testFile['destination'],
                    'contents' => $testFile['contents'],
                ]),
                $testFile['handle']
            );
        }
    }

    public function testTriggerUsingWildcard()
    {
        $this->dispatcher->trigger('create*');

        foreach ($this->testFiles as $testFile) {
            $this->assertTrue(file_exists($testFile['destination']));
            $this->assertSame($testFile['contents'], file_get_contents($testFile['destination']));
        }
    }

    public function testHasEvent()
    {
        foreach ($this->testFiles as $testFile) {
            $this->assertTrue($this->dispatcher->hasEvent($testFile['handle']));
        }

        $fakeEvents = [
            'fake.handle1',
            'fake.handle2',
            'fake.handle3',
            'fake.handle4',
        ];
        foreach ($fakeEvents as $fakeEvent) {
            $this->assertFalse($this->dispatcher->hasEvent($fakeEvent));
        }

        $wildcardEvents = [
            'create*',
            'create.testfil*',
        ];
        foreach ($wildcardEvents as $wildcardEvent) {
            $this->assertTrue($this->dispatcher->hasEvent($wildcardEvent));
        }
    }

    /**
     * @dataProvider dataTestFiles
     *
     * @param string $destination
     * @param string $contents
     * @param string $handle
     *
     * @throws \Exception
     */
    public function testTriggerWithoutWildcard(string $destination, string $contents, string $handle)
    {
        $this->dispatcher->trigger($handle);

        $this->assertTrue(file_exists($destination));
        $this->assertSame($contents, file_get_contents($destination));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Event 'missing-handle' does not exist
     */
    public function testTriggerExpectExceptionHandleDoesNotExist()
    {
        $this->dispatcher->trigger('missing-handle');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Failed Event: 'return-false'
     */
    public function testTriggerExpectExceptionFailedEvent()
    {
        $this->dispatcher->addEvent(
            new HandleMethodReturnsFalseEvent(),
            'return-false'
        );

        $this->dispatcher->trigger('return-false');
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Failed Events: 'return-false1, return-false2'
     */
    public function testTriggerExpectExceptionFailedEvents()
    {
        $this->dispatcher->addEvent(
            new HandleMethodReturnsFalseEvent(),
            'return-false1'
        );
        $this->dispatcher->addEvent(
            new HandleMethodReturnsFalseEvent(),
            'return-false2'
        );

        $this->dispatcher->trigger('return-fal*');
    }

    public function testTriggerSingleEventSuccess()
    {
        $this->dispatcher->addEvent(
            new HandleMethodReturnsTrueEvent(),
            'return-true1'
        );

        $this->assertNull($this->dispatcher->trigger('return-true1'));
    }


    public function testTriggerMultipleEventsSuccess()
    {
        $this->dispatcher->addEvent(
            new HandleMethodReturnsTrueEvent(),
            'return-true1'
        );
        $this->dispatcher->addEvent(
            new HandleMethodReturnsTrueEvent(),
            'return-true2'
        );

        $this->assertNull($this->dispatcher->trigger('return-t*'));
    }

    public function tearDown()
    {
        foreach ($this->testFiles as $testFile) {
            if (file_exists($testFile['destination'])) {
                unlink($testFile['destination']);
            }
        }
    }

    /**
     * @return array
     */
    public function dataTestFiles() : array
    {
        return [
            [
                'destination' => __DIR__ . '/../testfile1',
                'contents' => md5(__DIR__ . '/../testfile1'),
                'handle' => 'create.testfile1',
            ],[
                'destination' => __DIR__ . '/../testfile2',
                'contents' => md5(__DIR__ . '/../testfile2'),
                'handle' => 'create.testfile2',
            ],[
                'destination' => __DIR__ . '/../testfile3',
                'contents' => md5(__DIR__ . '/../testfile3'),
                'handle' => 'create.testfile3',
            ],[
                'destination' => __DIR__ . '/../testfile4',
                'contents' => md5(__DIR__ . '/../testfile4'),
                'handle' => 'create.testfile4',
            ],
        ];
    }
}
