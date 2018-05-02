<?php

namespace Mic2100\Events;

/**
 * Class Dispatcher
 *
 * @category Events
 * @package  Mic2100\Events
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
final class Dispatcher
{
    /**
     * Any string you want to use as the wildcard defaults to *
     *
     * @var string
     */
    private $wildcard = '*';

    /**
     * @var [EventInterface]
     */
    private $events = [];

    /**
     * Dispatcher constructor
     *
     * @param string|null $wildcard
     */
    public function __construct(string $wildcard = '*')
    {
        $this->wildcard = $wildcard;
    }

    /**
     * Adds the event to the dispatcher
     *
     * @param EventInterface $event
     * @param string|null $handle
     */
    public function addEvent(EventInterface $event, string $handle = null)
    {
        $handle = $handle ?? $event->getHandle();
        if (strpos($handle, $this->wildcard) !== false) {
            throw new \InvalidArgumentException(
                'The handle cannot contain the wildcard: ' . $handle
            );
        }
        $this->events[$handle] = $event;
    }

    /**
     * Removes the event from the dispatcher
     *
     * @param string $handle
     */
    public function removeEvent(string $handle)
    {
        if ($this->hasEvent($handle)) {
            unset($this->events[$handle]);
        } elseif ($this->isWildcardHandle($handle)) {
            $this->processWildcardRemoval($handle);
        }
    }

    /**
     * Has the event been added to the dispatcher
     *
     * @param string $handle
     * @return bool
     */
    private function hasEvent(string $handle) : bool
    {
        return isset($this->events[$handle]) || array_key_exists($handle, $this->events);
    }

    /**
     * Trigger the event using the handle
     *
     * The handle can also be a wildcard '*' doing this will run all events that
     * start with provided handle
     * Wildcard Handles will looks like:
     * email* - will run all events starting with e.g. email.read, email.read-box-2
     *
     * @param string $handle
     * @param array|null  $params
     *
     * @return null
     * @throws \Exception - if the event does not exist or the event(s) have failed
     */
    public function trigger(string $handle, array $params = null)
    {
        $isValidHandle = $this->hasEvent($handle);
        if ($isValidHandle) {
            if (!$this->handleEvent($this->events[$handle], $params)) {
                throw new \Exception(sprintf('Failed Event: \'%s\'', $handle));
            }
        } elseif (!$isValidHandle && $this->isWildcardHandle($handle)) {
            $eventsFailed = $this->processWildcardHandle($handle, $params);
            if ($eventsFailed) {
                throw new \Exception(sprintf('Failed Events: \'%s\'', implode(', ', $eventsFailed)));
            }
        } else {
            throw new \Exception(sprintf('Event \'%s\' does not exist', $handle));
        }
    }

    /**
     * Process wildcard handles.
     *
     * @param string $handle
     * @param array|null $params
     *
     * @return array
     */
    private function processWildcardHandle(string $handle, array $params = null) : array
    {
        $eventsFailed = [];
        $this->processMatchingWildcardEvents($handle, function ($handle, $event) use (&$eventsFailed, $params) {
            !$this->handleEvent($event, $params) && $eventsFailed[] = $handle;
        });

        return $eventsFailed;
    }

    /**
     * Process wildcard removal.
     *
     * @param string $handle
     */
    private function processWildcardRemoval(string $handle)
    {
        $this->processMatchingWildcardEvents($handle, function ($handle) {
            unset($this->events[$handle]);
        });
    }

    /**
     * This will iterate over the events and match anything that starts with
     * the provided handle minus the wildcard char, then call the callable
     * passing the handle as the first parameter and the event as the second.
     *
     * @param string $handle
     * @param callable $method
     */
    private function processMatchingWildcardEvents(string $handle, callable $method)
    {
        $startOfHandle = substr($handle, 0, strlen($this->wildcard));
        foreach ($this->events as $eventHandle => $event) {
            if (strpos($eventHandle, $startOfHandle) === 0) {
                $method($eventHandle, $event);
            }
        }
    }

    /**
     * Checks if the string ends with the wildcard char
     *
     * @param string $handle
     * @return bool
     */
    private function isWildcardHandle(string $handle) : bool
    {
        return substr($handle, -strlen($this->wildcard)) == $this->wildcard;
    }

    /**
     * @param EventInterface $event
     * @param array|null $params
     *
     * @return bool
     */
    private function handleEvent(EventInterface $event, array $params = null) : bool
    {
        return $event->handle($params);
    }
}
