<?php

namespace Mic2100\Events;

/**
 * Trait DispatcherAwareTrait
 *
 * @category Events
 * @package  Mic2100\Events
 * @author   Michael Bardsley @mic_bardsley
 * @link     http://github.com/mic2100/events
 * @licence  MIT
 */
trait DispatcherAwareTrait
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * Gets the dispatcher
     *
     * @return Dispatcher
     */
    public function getDispatcher() : Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * Sets the dispatcher
     *
     * @param Dispatcher $dispatcher
     */
    public function setDispatcher(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Triggers the event(s) given the handle
     *
     * @param string $handle
     * @param array|null $params
     *
     * @throws \Exception
     */
    public function dispatcherTriggerEvent(string $handle, array $params = null)
    {
        $this->getDispatcher()->trigger($handle, $params);
    }

    /**
     * Removes the event(s) from the dispatcher
     *
     * @param string $handle
     */
    public function dispatcherRemoveEvent(string $handle)
    {
        $this->getDispatcher()->removeEvent($handle);
    }

    /**
     * Adds an event to the dispatcher
     *
     * @param EventInterface $event
     * @return bool
     */
    public function dispatcherAddEvent(EventInterface $event) : bool
    {
        $this->getDispatcher()->addEvent($event);
    }

    /**
     * Has the dispatcher got the event?
     *
     * @param string $handle
     * @return bool
     */
    public function dispatcherHasEvent(string $handle) : bool
    {
        return $this->getDispatcher()->hasEvent($handle);
    }
}
