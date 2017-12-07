<?php

namespace Mic2100\Events;

/**
 * Class AbstractEvent
 *
 * @category Events
 * @package  Mic2100\Events
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
abstract class AbstractEvent implements EventInterface
{
    const HANDLE = '';

    /**
     * $params will consist of what ever variables are needed throughout the event
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (empty(static::HANDLE)) {
            throw new \InvalidArgumentException(
                sprintf('Missing handle for event: %s', get_class($this))
            );
        }
    }

    /**
     * Handle the event
     *
     * @return bool true/false completed/failed
     */
    public function handle(): bool
    {
        throw new \RuntimeException(
            sprintf('Missing handle functionality for event: %s', get_class($this))
        );
    }
}
