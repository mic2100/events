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
     * Trigger the event using the handle
     *
     * The handle can also be a wildcard '*' doing this will run all events that start with provided handle
     * Wildcard Handles will looks like:
     * email* - will run all events starting with e.g. email.read, email.read-box-2 or emails
     *
     * @param string $handle
     * @throws \Exception - if the event does not exist or the event(s) have failed
     */
    public function trigger(string $handle)
    {
        if (isset($this->events[$handle]) || array_key_exists($handle, $this->events)) {
            if (!$this->events[$handle]->handle()) {
                throw new \Exception(sprintf('Failed Event: %s', $handle));
            }
        }

        if ($this->isWildcardHandle($handle)) {
            $eventsFailed = $this->processWildcardHandle($handle);
            if ($eventsFailed) {
                throw new \Exception(sprintf('Failed Events: \'%s\'', implode(', ', $eventsFailed)));
            }

            return;
        }

        throw new \Exception(sprintf('Event \'%s\' does not exist', $handle));
    }

    /**
     * Process wildcard handles.
     *
     * This will iterate over the events and match anything that starts with
     * the provided handle minus the wildcard char.
     *
     * @param string $handle
     * @return array
     */
    private function processWildcardHandle(string $handle) : array
    {
        $startOfHandle = substr($handle, 0, -1);
        $eventsFailed = [];
        foreach ($this->events as $eventHandle => $event) {
            if (strpos($eventHandle, $startOfHandle) === 0 && !$event->handle()) {
                $eventsFailed[] = $eventHandle;
            }
        }

        return $eventsFailed;
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
}
