<?php

namespace Mic2100\Events;

/**
 * Interface EventInterface
 *
 * @category Events
 * @package  Mic2100\Events
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
interface EventInterface
{
    /**
     * The handle that will be used when calling the event
     *
     * @var string
     */
    const HANDLE = '';

    /**
     * $params will consist of what ever variables are needed throughout the event
     *
     * @param array $params
     */
    public function __construct(array $params = []);

    /**
     * Handle the event
     *
     * @return bool true/false completed/failed
     */
    public function handle() : bool;
}
