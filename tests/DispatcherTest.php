<?php

namespace Mic2100EventsTests;

use Mic2100\Events\Dispatcher;
use Mic2100EventsTests\Events\FileCreationEvent;
use PHPUnit\Framework\TestCase;

class DispatcherTest extends TestCase
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    public function setUp()
    {
        $this->dispatcher = new Dispatcher;

        $this->dispatcher->addEvent(
            new FileCreationEvent([
                'destination' => __DIR__ . '/../testfile1',
                'contents' => md5(__DIR__ . '/../testfile1'),
            ]),
            'create.testfile1'
        );
        $this->dispatcher->addEvent(
            new FileCreationEvent([
                'destination' => __DIR__ . '/../testfile2',
                'contents' => md5(__DIR__ . '/../testfile2'),
            ]),
            'create.testfile2'
        );
        $this->dispatcher->addEvent(
            new FileCreationEvent([
                'destination' => __DIR__ . '/../testfile3',
                'contents' => md5(__DIR__ . '/../testfile3'),
            ]),
            'create.testfile3'
        );
        $this->dispatcher->addEvent(
            new FileCreationEvent([
                'destination' => __DIR__ . '/../testfile4',
                'contents' => md5(__DIR__ . '/../testfile4'),
            ]),
            'create.testfile4'
        );
    }

    public function testTrigger()
    {
        $this->dispatcher->trigger('create*');
    }

    public function tearDown()
    {
        if (file_exists(__DIR__ . '/../testfile1')) {
            unlink(__DIR__ . '/../testfile1');
        }

        if (file_exists(__DIR__ . '/../testfile2')) {
            unlink(__DIR__ . '/../testfile2');
        }

        if (file_exists(__DIR__ . '/../testfile3')) {
            unlink(__DIR__ . '/../testfile3');
        }

        if (file_exists(__DIR__ . '/../testfile4')) {
            unlink(__DIR__ . '/../testfile4');
        }
    }
}
