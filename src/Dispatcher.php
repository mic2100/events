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
     * @var [EventInterface]
     */
    private $events = [];

    /**
     * @var Configuration
     */
    private $config;

    /**
     * Dispatcher constructor
     *
     * @param Configuration|null $config - if null the default configuration is instantiated
     */
    public function __construct(Configuration $config = null)
    {
        $this->config = $config ?? new Configuration;
    }

    /**
     * Adds the event to the dispatcher
     *
     * @param EventInterface $event
     * @param string|null $handle
     */
    public function addEvent(EventInterface $event, string $handle = null)
    {
        $this->events[$handle ?? $event::HANDLE] = $event;
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
     * @return null
     * @throws \Exception - if the event does not exist or the event(s) have failed
     */
    public function trigger(string $handle)
    {
        $isValidHandle = $this->hasEvent($handle);
        if ($isValidHandle) {
            if ($this->handleEvent($this->events[$handle])) {
                return;
            } else {
                throw new \Exception(sprintf('Failed Event: \'%s\'', $handle));
            }
        } elseif (!$isValidHandle && $this->isWildcardHandle($handle)) {
            $eventsFailed = $this->processWildcardHandle($handle);
            if ($eventsFailed) {
                throw new \Exception(sprintf('Failed Events: \'%s\'', implode(', ', $eventsFailed)));
            }
        } else {
            throw new \Exception(sprintf('Event \'%s\' does not exist', $handle));
        }

        return;
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
        $startOfHandle = substr($handle, 0, $this->config->getWildcardLength());
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
        return substr($handle, -1) == $this->config->getWildcard();
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
