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
    /**
     * @var string
     */
    protected $handle = '';

    /**
     * @var array
     */
    protected $params;

    /**
     * $params will consist of what ever variables are needed throughout the event
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        if (empty($this->handle)) {
            throw new \InvalidArgumentException(
                sprintf('Missing handle for event: %s', get_class($this))
            );
        }

        $this->params = $params;
    }

    /**
     * Handle the event
     *
     * @param array|null $params
     *
     * @return bool true/false completed/failed
     */
    public function handle(array $params = null) : bool
    {
        throw new \RuntimeException(
            sprintf('Missing handle functionality for event: %s', get_class($this))
        );
    }

    /**
     * Gets the handle used to identify the event
     *
     * @return string
     */
    public function getHandle() : string
    {
        return $this->handle;
    }

    /**
     * Gets the params that have been set
     *
     * @return array
     */
    public function getParams() : array
    {
        return $this->params;
    }
}
