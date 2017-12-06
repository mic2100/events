<?php

namespace Mic2100\Events;

/**
 * Class Dispatcher
 *
 * @package Mic2100\Events
 */
final class Dispatcher
{
    /**
     * @var [EventInterface]
     */
    private $events = [];

    /**
     * @var string
     */
    private $wildcard = '*';

    /**
     * Dispatcher constructor
     *
     * Config can be:
     * Key: wildcard - Value: any string with length of 1 you want to use as the wildcard defaults to *
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        isset($config['wildcard']) && strlen($config['wildcard']) == 1 && $this->wildcard = $config['wildcard'];
    }

    /**
     * Adds the event to the dispatcher
     *
     * @param EventInterface $event
     */
    public function addEvent(EventInterface $event)
    {
        $this->events[$event::HANDLE] = $event;
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
     * @throws \Exception - if the event does not exist or the event(s) have failed
     */
    public function trigger(string $handle)
    {
        if ($this->hasEvent($handle) && !$this->handleEvent($this->events[$handle])) {
            throw new \Exception(sprintf('Failed Event: %s', $handle));
        } elseif ($this->isWildcardHandle($handle)) {
            $eventsFailed = $this->processWildcardHandle($handle);
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
     * @return array
     */
    private function processWildcardHandle(string $handle) : array
    {
        $eventsFailed = [];
        $this->processMatchingWildcardEvents($handle, function ($handle, $event) use (&$eventsFailed) {
            !$this->handleEvent($event) && $eventsFailed[] = $handle;
        });

        return $eventsFailed;
    }

    /**
     * Process wildcard removal.
     *
     * @param string $handle
     * @return array
     */
    private function processWildcardRemoval(string $handle) : array
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
        $startOfHandle = substr($handle, 0, -1);
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
        return substr($handle, -1) == $this->wildcard;
    }

    /**
     * @param EventInterface $event
     * @return bool
     */
    private function handleEvent(EventInterface $event) : bool
    {
        return $event->handle();
    }
}
